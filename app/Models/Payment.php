<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id', 'id');
    }
}
