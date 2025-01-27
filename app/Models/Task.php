<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'completions',
        'submitions',
        'time_limit',
        'memory_limit',
        'definition',
        'input_definition',
        'output_definition',
        'examples',
    ];
}
