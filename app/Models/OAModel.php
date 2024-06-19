<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OAModel extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'models';
    protected $fillable = [
        'name',
        'description'
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
