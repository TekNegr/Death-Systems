<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    protected $fillable = [
        'title',
        'company',
        'location',
        'description',
        'employment_type',
        'salary',
        'start_date',
        'end_date',
        'contact_email',
        'website',
        'application_link',
        'status',
        'category',
    ];
}
