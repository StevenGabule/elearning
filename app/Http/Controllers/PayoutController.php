<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayoutController extends Controller
{
    public function store(Request $request)
    {
        $courseId = $request->input('course_id');
        $payments = Payment::where('course_id', '=', $courseId)->get();

        $authorEarning = 0;
        $netEarning = 0;
        $totalRefund = 0;

        foreach ($payments as $payment) {
            if ($payment->status == 'finalized') {
                $authorEarning += $payment->author_earning;
            } else {
                $totalRefund += $payment->author_earning;
            }
            $netEarning += $payment->amount;
        }

        Payout::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'net_earnings' => $netEarning,
            'total_author_earnings' => $authorEarning,
            'total_refunds' => $totalRefund,
            'comment' => $request->input('comment'),
        ]);
        return response()->json(['successful' => true], 201);
    }
}
