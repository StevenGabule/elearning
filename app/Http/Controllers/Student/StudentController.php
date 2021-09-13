<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $id = auth()->id();
        $enrolled = Enrollment::with(['course.author'])->where("user_id", $id)->get();
        return view('frontend.students.show', compact('enrolled'));
    }

    /*
     * Student request a refund
     * @param $course_id
     * */
    public function refund(Request $request)
    {
        $user_id = auth()->id();
        $course_id = $request->input('course_id');
        $course = Course::where('id', $course_id)->first();

        // fetch the current payment details
        $payment = Payment::with('payer')->where([
            ['payer_id', '=', $user_id],
            ['course_id', '=', $course_id]
        ])->first();

        // record a refund information
        $refund = Refund::create([
            'uuid' => Str::uuid(),
            'requester_id' => $user_id,
            'payment_id' => $payment->id,
            'transaction_id' => null,
            'amount' => $payment->amount,
            'course_id' => $course_id,
            'comment' => $request->input('comment')
        ]);

        // create a transaction record for requesting a refund by the student
        $transaction = Transaction::create([
            'user_id' => $user_id,
            'uuid' => Str::uuid(),
            'type' => 'debit',
            'description' => 'Refund',
            'long_description' => 'Refund of "' . $course->title . '" purchased by ' . $payment->payer->first_name . ' ' . $payment->payer->last_name,
            'amount' => -$payment->author_earning
        ]);

        // update the transaction id
        $refund->transaction_id = $transaction->id;
        $refund->save();

        return response()->json(['success' => true]);
    }
}
