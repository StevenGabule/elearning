<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $id = auth()->id();
        $enrolled = Enrollment::with(['course'])->where("user_id", $id)->get();
        return view('frontend.students.show', compact('enrolled'));
    }
}
