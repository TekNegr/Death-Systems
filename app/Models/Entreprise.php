<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'recipient_name',
        'recipient_gender',
        'email_to_apply',
        'work_domain',
    ];
}
