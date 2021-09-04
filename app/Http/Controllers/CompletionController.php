<?php

namespace App\Http\Controllers;

use App\Models\Completion;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CompletionController extends Controller
{
    public function store(Request $request)
    {
        $course_id = $request->course_id;
        $user_id = $request->user_id;
        $result = Completion::create($request->except('_token', 'course_id'));
        if($result) {
            $lessons = Lesson::where('course_id', $course_id)->get();
            foreach ($lessons as $lesson) {
                $checkCompletion = Completion::where([
                    ['lesson_id', '=', $lesson->id],
                    ['user_id', '=', $user_id],
                ])->first();
                if (!$checkCompletion) {
                    return response()->json(['ok' => true, 'completed' => false]);
                }
            }
            return response()->json(['ok' => true, 'completed' => true]);
        }
        return response()->json(['ok' => true, 'completed' => false]);
    }
}
