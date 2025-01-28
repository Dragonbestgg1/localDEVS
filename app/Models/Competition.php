<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'time',
        'from',
        'till',
        'description',
        'information',
        'difficulty',
        'tasks',
    ];
}
