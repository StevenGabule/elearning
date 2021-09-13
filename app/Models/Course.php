<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $guarded = [];
    protected static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class,  'course_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
