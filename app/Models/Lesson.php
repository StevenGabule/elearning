<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function showContentType()
    {
        if($this->content_type == "video") {
            return "<i class='bi-play-circle'></i>";
        }
        if($this->content_type == "youtube") {
            return "<i class='bi-play-btn-fill'></i>";
        }
        if($this->content_type == "article") {
            return "<i class='bi-file-earmark'></i>";
        }
        if($this->content_type == "quiz") {
            return "<i class='bi-pen'></i>";
        }
    }
}
