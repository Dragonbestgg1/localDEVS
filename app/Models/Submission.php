<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'submitions';

    protected $fillable = [
        'code',
        'name',
        'author',
        'submitted',
        'programming_language',
        'time_taken',
        'memory',
        'status',
        'tests',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'code', 'code');
    }
}
