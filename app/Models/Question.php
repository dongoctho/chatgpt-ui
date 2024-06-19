<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $fillable = [
        'content',
        'thread_id',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function answers()
    {
        return $this->hasOne(Answer::class);
    }
}
