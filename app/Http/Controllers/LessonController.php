<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index()
    {
        //
    }

    public function createLesson(Course $course, Section $section)
    {
        return view('frontend.lessons.create', compact('course', 'section'));
    }

    public function store(Request $request)
    {
        $preview = $request->exists('preview');
        $data = $request->except('_token', 'preview');
        $data['preview'] = $preview;
        $data['uuid'] = Str::uuid();
        $data['sortOrder'] = 0;
        $lesson = Lesson::create($data);
        return redirect()->route('lesson.upload.store', [
            'course' => $request->course_id ,
            'section' => $request->section_id,
            'lesson' => (int)$lesson->id,
        ]);
    }

    public function upload(Course $course, Section $section, Lesson $lesson)
    {
        return view('frontend.lessons.upload', compact('course', 'section', 'lesson'));
    }


    public function show(Lesson $lesson)
    {
        //
    }

    public function edit(Lesson $lesson)
    {
        //
    }

    public function update(Request $request, Lesson $lesson)
    {
        //
    }

    public function destroy(Lesson $lesson)
    {
        //
    }
}
