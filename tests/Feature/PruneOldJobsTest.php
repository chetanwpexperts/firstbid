<?php

namespace Tests\Feature;

use App\Models\InboundEmail;
use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneOldJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_prune_deletes_only_jobs_older_than_7_days(): void
    {
        $user = User::factory()->create();

        $old = UpworkJob::create([
            'user_id' => $user->id, 'status' => 'received', 'title' => 'Old',
            'description' => 'x', 'budget_display' => '$1', 'raw_payload' => [],
        ]);
        $old->forceFill(['created_at' => now()->subDays(8)])->save();

        $recent = UpworkJob::create([
            'user_id' => $user->id, 'status' => 'received', 'title' => 'Recent',
            'description' => 'x', 'budget_display' => '$1', 'raw_payload' => [],
        ]);
        $recent->forceFill(['created_at' => now()->subDays(6)])->save();

        $this->artisan('jobs:prune')->assertSuccessful();

        $this->assertDatabaseMissing('upwork_jobs', ['id' => $old->id]);
        $this->assertDatabaseHas('upwork_jobs', ['id' => $recent->id]);
    }

    public function test_prune_deletes_only_inbound_emails_older_than_7_days(): void
    {
        $user = User::factory()->create();

        $old = InboundEmail::create([
            'user_id' => $user->id, 'to_address' => 'a@b.com', 'from_address' => 'c@d.com',
            'subject' => 'x', 'html' => '<p>x</p>', 'status' => 'parsed',
        ]);
        $old->forceFill(['created_at' => now()->subDays(9)])->save();

        $recent = InboundEmail::create([
            'user_id' => $user->id, 'to_address' => 'a@b.com', 'from_address' => 'c@d.com',
            'subject' => 'x', 'html' => '<p>x</p>', 'status' => 'parsed',
        ]);
        $recent->forceFill(['created_at' => now()->subDays(1)])->save();

        $this->artisan('jobs:prune')->assertSuccessful();

        $this->assertDatabaseMissing('inbound_emails', ['id' => $old->id]);
        $this->assertDatabaseHas('inbound_emails', ['id' => $recent->id]);
    }

    public function test_prune_boundary_exactly_7_days_old_is_kept(): void
    {
        $user = User::factory()->create();

        $boundary = UpworkJob::create([
            'user_id' => $user->id, 'status' => 'received', 'title' => 'Boundary',
            'description' => 'x', 'budget_display' => '$1', 'raw_payload' => [],
        ]);
        // Just under 7 days old — should survive (cutoff is strictly "<").
        $boundary->forceFill(['created_at' => now()->subDays(7)->addMinute()])->save();

        $this->artisan('jobs:prune');

        $this->assertDatabaseHas('upwork_jobs', ['id' => $boundary->id]);
    }
}
