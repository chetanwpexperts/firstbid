<?php

namespace App\Models;

use App\Helpers\HashId;
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
        'opener_hooks'        => 'array',
        'milestones'          => 'array',
        'matched_portfolio'   => 'array',
        'applied_at'          => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsAppliedAttribute(): bool
    {
        return $this->status === 'applied' || $this->applied_at !== null;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->is_applied) {
            return 'applied';
        }
        return match ($this->status) {
            'ready_to_generate' => 'ready',
            default             => $this->status,
        };
    }

    public function getHashIdAttribute(): string
    {
        return HashId::encode($this->id);
    }

    public function getRouteKey()
    {
        return $this->hash_id;
    }
}
