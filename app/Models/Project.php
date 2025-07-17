<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'pictures',
        'links',
    ];

    protected $casts = [
        'pictures' => 'array',
        'links' => 'array',
    ];
}
