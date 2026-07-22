<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpworkJob extends Model
{
    protected $guarded = [];

    protected $casts = [
        'raw_payload'         => 'array',
        'screening_questions' => 'array',
        'question_answers'    => 'array',
        'payment_verified'    => 'boolean',
        'task_breakdown'      => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'ready_to_generate' => 'ready',
            default             => $this->status,
        };
    }
}
