<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buyNow(Request $request)
    {
        $rules = [
            'value' => ['required', 'numeric', 'min:5'],
            'currency' => ['required'],
            'course_id' => ['required', 'exists:courses,id'],
        ];

        $request->validate($rules);

        $paymentPlatForm = resolve(PayPalService::class);

        return $paymentPlatForm->handlePayment($request);
    }

    public function approval()
    {
        $paymentPlatForm = resolve(PayPalService::class);
        return $paymentPlatForm->handleApproval();
    }

    public function cancelled() {}

    public function refund(Request $request)
    {
        $paymentPlatForm = resolve(PayPalService::class);
        return $paymentPlatForm->handleRefund($request->id, $request->pid);
    }
}
