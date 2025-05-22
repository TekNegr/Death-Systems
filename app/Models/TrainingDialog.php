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

    public function getUserMessageAttribute($value)
    {
        return json_decode($value);
    }

    public function getAiResponseAttribute($value)
    {
        return json_decode($value);
    }

    public function getRecoveryAnswerAttribute($value)
    {
        return json_decode($value);
    }
}
