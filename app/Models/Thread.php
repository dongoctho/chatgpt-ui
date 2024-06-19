<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'threads';
    protected $fillable = [
        'title',
        'oa_thread_id',
        'assistant_id',
        'user_id',
        'model_id',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function model()
    {
        return $this->belongsTo(OAModel::class);
    }
}
