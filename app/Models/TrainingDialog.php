<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingDialog extends Model
{
    protected $fillable = [
        'user_message',
        'ai_response',
        'recovery_answer',
        'score',
    ];
}
