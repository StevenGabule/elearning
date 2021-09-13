<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Transaction;
use App\Traits\ConsumesExternalServices;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayPalService
{

    use ConsumesExternalServices;

    protected $baseUri;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUri = config('services.paypal.base_uri');
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
        return "Basic {$credentials}";
    }

    public function handlePayment(Request $request)
    {
        $order = $this->createOrder($request->value, $request->currency);
        $orderLinks = collect($order->links);
        $approve = $orderLinks->where('rel', 'approve')->first();
        session()->put('approvalId', $order->id);
        session()->put('course_id', $request->course_id);
        return redirect($approve->href);
    }

    public function handleApproval()
    {
        if (session()->has('approvalId') && session()->has('course_id')) {
            $approvalId = session()->get("approvalId");
            $courseId = session()->get("course_id");
            $user_id = auth()->id();
            $payment = $this->capturePayment($approvalId);
            $refund_id = $payment->purchase_units[0]->payments->captures[0]->id;
            $name = $payment->payer->name->given_name;
            $payment = $payment->purchase_units[0]->payments->captures[0]->amount;
            $amount = $payment->value;
            $currency = $payment->currency_code;

            $course = Course::where('id', $courseId)->first();

            // create a record for transactions and payments
            $transaction = Transaction::create([
                'uuid' => Str::uuid(),
                'user_id' => $user_id,
                'type' => 'debit',
                'description' => 'Purchased the course name ' . $course->title,
                'amount' => $course->price
            ]);

            $authorEarning = ($amount * 40) / 100;

            Payment::create([
                'uuid' => Str::uuid(),
                'course_id' => $courseId,
                'payer_id' => $user_id,
                'transaction_id' => $transaction->id,
                'payment_method' => 'PayPal',
                'description' => 'sale',
                'amount' => $course->price,
                'author_earning' => $authorEarning,
                'refund_deadline' => Carbon::now()->addMonth(),
                'refund_id' => $refund_id
            ]);

            Enrollment::create(['course_id' => $courseId, 'user_id' => $user_id]);

            return redirect()->route('student.take_course', ['slug' => $course->slug])->with(['success' => [
                'payment' => "Thanks, {$name}. We received your {$amount}{$currency} payment."]
            ]);
        }
        return redirect()->route('homepage')->withErrors('We cannot capture the payment. Try again, please');
    }

    public function createOrder($value, $currency)
    {
        return $this->makeRequest('POST',
            '/v2/checkout/orders',
            [],
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    0 => [
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => $value
                        ]
                    ]
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('approved'),
                    'cancel_url' => route('cancelled'),
                ]
            ],
            [],
            $isJsonRequest = true);
    }

    public function capturePayment($approvalId)
    {
        return $this->makeRequest(
            'POST',
            "/v2/checkout/orders/{$approvalId}/capture",
            [],
            [],
            [
                'Content-Type' => 'application/json',
                ''
            ]
        );
    }

    public function handleRefund($refundId, $pid)
    {
        // send a request for a refund
        $refund = $this->makeRequest(
            'POST',
            "/v2/payments/captures/{$refundId}/refund",
            [],
            [],
            [
                'Content-Type' => 'application/json',
                ''
            ]
        );

        // after refund submitted we will delete the enrolled student in the course
        $getRefund = Payment::where('refund_id', '=', $refundId)->first();

        $enrolled = Enrollment::where([
            ['course_id', '=', $getRefund->course_id],
            ['user_id', '=', $getRefund->payer_id]
        ])->first();

        $enrolled->delete();

        // update the current payment information
        $getRefund->update([
           'refunded_at' => Carbon::now(),
           'status' => 'refunded'
        ]);

        // update the current refund information
        Refund::where('id', $pid)->update([
           'processed_at' => Carbon::now(),
           'status' => 'closed'
        ]);

        return response()->json(['response' => $refund]);
    }
    public function handlePayoutPayments()
    {

    }
}
