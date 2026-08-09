<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChromeReviewerSeeder extends Seeder
{
    /**
     * Run the database seeds for Google Chrome Web Store Reviewer Account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@firstbidin.com'],
            [
                'name'                => 'Chrome Store Reviewer',
                'password'            => Hash::make('FirstBid2026!'),
                'webhook_token'       => 'demo_reviewer_token_2026',
                'is_approved'         => true,
                'is_admin'            => false,
                'plan'                => 'pro',
                'letters_quota'       => 1000,
                'letters_used'        => 0,
            ]
        );
    }
}
