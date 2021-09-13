<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Video;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $courses = Course::with([
            'author:id,first_name,last_name,avatar,created_at',
            'category' => function($query) {
                $query->where('live', true);
            }])
            ->where([
                ['approved', '=', true],
                ['published', '=', true],
            ])->orderByDesc('created_at')->paginate(12);

        return view('index', compact('courses'));
    }

    public function showCourse($slug)
    {
        $course = Course::with([
            'sections.lessons',
            'author:id,first_name,last_name,avatar,bio,created_at',
            'category:id,name,slug'])
            ->where([
                ['approved', '=', true],
                ['published', '=', true],
                ['slug', '=', $slug],
            ])->firstOrFail();

        $id = $course->sections[0]->lessons[0]->id;
        $video = Video::where('lesson_id', $id)->first();

        return view('show_course', compact('course', 'video'));
    }

    public function learnCourse($slug, $lessonId=0)
    {
        $course = Course::with([
            'sections.lessons.video',
            'author:id,first_name,last_name,avatar,bio,tagline,created_at',
            'category:id,name,slug'])
            ->where([
                ['approved', '=', true],
                ['published', '=', true],
                ['slug', '=', $slug],
            ])->firstOrFail();

        // check the user is enrolled in this course
        $checkEnroll = Enrollment::where([
            ['course_id', '=', $course->id],
            ['user_id', '=', auth()->id()]
        ])->first();

        if (!$checkEnroll) {
            return redirect()->route('homepage')->with(['error' => [
                'message' => "Upon validation, your not enrolled in this course. Thank you!"]
            ]);
        }

        if($lessonId != 0) {
            $video = Video::where('lesson_id', $lessonId)->firstOrFail();

            return view('frontend.students.index', compact('course', 'video', 'lessonId'));
        }

        $id = $course->sections[0]->lessons[0]->id;
        $video = Video::where('lesson_id', $id)->first();

        return view('frontend.students.index', compact('course', 'video', 'lessonId'));
    }

    public function instructor()
    {
        return view('instructor');
    }

    public function cart()
    {
        return view('cart');
    }

    /*display instructor profile*/
    public function instructorProfile()
    {
        $courses = Course::with(['enrollments', 'payments'])->where('user_id', auth()->id())->get();
        return view('frontend.instructor.profile', compact('courses'));
    }
}
