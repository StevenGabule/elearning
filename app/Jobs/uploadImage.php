<?php

namespace App\Jobs;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class uploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $course;
    /**
     * Create a new job instance.
     *
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = 'public';
        $image = $this->course->image;
        $original_file = storage_path() . '/uploads/original/' . $image;
        try {
            Image::make($original_file)->fit(240, 135, function($constraint) {
                $constraint->aspectRatio();
            })->save($original = storage_path('uploads/thumbnail/' . $image));

            if (Storage::disk($disk)->put('uploads/courses/thumbnail/' . $image, fopen($original_file, 'r+'))) {
                \File::delete($original_file);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
