<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Jobs\{CreateVideoThumbnail, ConvertForStreaming};
use App\Models\Lesson;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function store(Lesson $lesson)
    {
        $video = $lesson->video()->create([
            'lesson_id' => $lesson->id,
            'title' => request()->title,
            'path' => request()->video->store("public/lesson/$lesson->id")
        ]);

        /** @var Video $video */
        $this->dispatch(new CreateVideoThumbnail($video));
        $this->dispatch(new ConvertForStreaming($video));
        return $video;
    }

    public function show(Video $video)
    {
        if (request()->wantsJson()) {
            return $video;
        }
        return view('video', compact('video'));
    }
    public function updateViews(Video $video)
    {
        $video->increment('views');
        return response()->json([]);
    }
}
