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
        'plan',
        'trial_ends_at',
        'letters_used',
        'letters_quota',
        'quota_reset_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'webhook_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'trial_ends_at'     => 'datetime',
            'quota_reset_at'    => 'datetime',
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
