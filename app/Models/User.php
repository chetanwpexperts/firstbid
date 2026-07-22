<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'webhook_token',
        'proposal_profile',
        'telegram_chat_id',
        'min_score',
        'min_score_operator',
        'auto_generate',
        'skip_unverified_payment',
        'plan',
        'trial_ends_at',
        'letters_used',
        'letters_quota',
        'quota_reset_at',
        'last_seen_jobs_at',
        'is_approved',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'webhook_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'trial_ends_at'           => 'datetime',
            'quota_reset_at'          => 'datetime',
            'last_seen_jobs_at'       => 'datetime',
            'auto_generate'           => 'boolean',
            'skip_unverified_payment' => 'boolean',
            'is_approved'              => 'boolean',
            'is_admin'                 => 'boolean',
        ];
    }

    public function upworkJobs()
    {
        return $this->hasMany(UpworkJob::class);
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && now()->lessThan($this->trial_ends_at);
    }

    public function canGenerate(): bool
    {
        return $this->plan === 'pro' || $this->onTrial();
    }
}
