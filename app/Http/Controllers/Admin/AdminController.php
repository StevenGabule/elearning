<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Announcement,
    Approval,
    Category,
    Comment,
    Completion,
    Course,
    Enrollment,
    Payment,
    Refund,
    Payout,
    Review,
    Transaction,
    User};
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        return view('backend.index');
    }

    public function student(Request $request)
    {
        if ($request->ajax()) {
            $students = User::where('user_type', 2)->orderByDesc('created_at')->get();

            return DataTables::of($students)->addColumn('action', static function ($data) {
                $status = $data->active == 1 ? 'Enable' : 'Disabled';
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> $status
                                </a>
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('backend.students.index');
    }

    public function course(Request $request)
    {
        if ($request->ajax()) {
            $courses = Course::with('author')->latest()->orderByDesc('created_at')->get();

            return DataTables::of($courses)->addColumn('action', static function ($data) {
                $status = $data->active == 1 ? 'Enable' : 'Disabled';
                $checkApproval = $data->approved ? "Disapproved" : "Approve";
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small btn-approval'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-check pr-2"></i> $checkApproval
                                </a>
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }

        return view('backend.courses.index');
    }

    public function instructor(Request $request)
    {
        if ($request->ajax()) {
            $instructors = User::where('user_type', 1)->orderByDesc('created_at')->get();

            return DataTables::of($instructors)->addColumn('action', static function ($data) {
                $status = $data->active == 1 ? 'Enable' : 'Disabled';
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> $status
                                </a>
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('backend.instructors.index');
    }

    public function transaction(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::with('user')->orderByDesc('created_at')->get();
            return DataTables::of($transactions)->addColumn('action', static function ($data) {
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">

                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }

        return view('backend.transactions.index');
    }

    public function payment(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::with(['course', 'payer'])->orderByDesc('created_at')->get();
            return DataTables::of($payments)->addColumn('action', static function ($data) {
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">

                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('backend.payments.index');
    }

    public function enrollment(Request $request)
    {
        if ($request->ajax()) {
            $enrollments = Enrollment::with(['course', 'user'])->orderByDesc('created_at')->get();
            return DataTables::of($enrollments)->addColumn('action', static function ($data) {
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> View
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('backend.enrollments.index');
    }

    public function completion()
    {
        $completions = Completion::orderByDesc('created_at')->get();
        return view('backend.completions.index', compact('completions'));
    }

    public function comment()
    {
        $comments = Comment::orderByDesc('created_at')->get();
        return view('backend.comments.index', compact('comments'));
    }

    public function review()
    {
        $reviews = Review::orderByDesc('created_at')->get();
        return view('backend.reviews.index', compact('reviews'));
    }

    public function approval()
    {
        $approvals = Approval::orderByDesc('created_at')->get();
        return view('backend.approvals.index', compact('approvals'));
    }

    public function payout(Request $request)
    {
        if ($request->ajax()) {
            $payouts = Payout::with('user')->orderByDesc('created_at')->get();
            return DataTables::of($payouts)->addColumn('action', static function ($data) {
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                 Process
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }

        return view('backend.payouts.index');
    }

    public function refund(Request $request)
    {
        if ($request->ajax()) {
            $refunds = Refund::with(['user', 'payment', 'transaction', 'course'])->get();
            return DataTables::of($refunds)->addColumn('action', static function ($data) {
                $refundId = $data->payment->refund_id;
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small btn-refund'
                                  href='javascript:void(0)'
                                  data-id="$data->id"
                                  id="{$refundId}">
                                  <i class="bi-eye-slash pr-2"></i> Confirm Refund
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('backend.refunds.index');
    }

    public function announcement()
    {
        $announcements = Announcement::orderByDesc('created_at')->get();
        return view('backend.announcements.index', compact('announcements'));
    }

    public function approvalCourse(Request $request)
    {
        $course = Course::where('id', $request->id)->first();
        if ($course) {
            $course->approved = !$course->approved;
            $course->published = !$course->published;
            $course->save();
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function category(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::orderByDesc('created_at')->get();
            return DataTables::of($categories)->addColumn('action', static function ($data) {
                $btnEnabled = $data->live ? "Disable" : "Enable";
                return <<<EOT
                        <div class="btn-group btn-group-sm dropleft">
                             <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small>Actions</small>
                             </button>
                             <div class="dropdown-menu small">
                                <a class='dropdown-item small btn-live'
                                  href='javascript:void(0)'
                                  id="$data->id">
                                  <i class="bi-eye-slash pr-2"></i> $btnEnabled
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class="bi-pencil pr-2"></i> Edit
                                </a>
                                <a class='dropdown-item small' id='$data->id' href='javascript:void(0)'>
                                  <i class='bi-trash pr-2'></i> Remove
                                </a>
                            </div>
                        </div>
EOT;
            })->rawColumns(['action'])->make(true);
        }
        return view('backend.categories.index');
    }

    public function categoryLive(Request $request)
    {
        if ($request->ajax()) {
            $category = Category::where('id', $request->id)->first();
            if ($category) {
                $category->live = !$category->live;
                $category->updated_at = now();
                $category->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }
    }

}
