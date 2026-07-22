<?php

namespace Tests\Feature;

use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardWindowFilterTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::factory()->create();
    }

    private function makeJob(User $user, \Illuminate\Support\Carbon $createdAt, string $title): UpworkJob
    {
        $job = UpworkJob::create([
            'user_id'        => $user->id,
            'status'         => 'received',
            'title'          => $title,
            'description'    => 'desc',
            'budget_display' => '$50/hr',
            'raw_payload'    => [],
        ]);
        $job->forceFill(['created_at' => $createdAt])->save();

        return $job;
    }

    public function test_default_window_is_24h_and_excludes_older_jobs(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user, now()->subHours(2), 'Recent job');
        $this->makeJob($user, now()->subHours(30), 'Old job (30h)');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Recent job');
        $response->assertDontSee('Old job (30h)');
    }

    public function test_window_3d_includes_jobs_within_3_days_excludes_older(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user, now()->subDays(2), 'Within 3 days');
        $this->makeJob($user, now()->subDays(5), 'Older than 3 days');

        $response = $this->actingAs($user)->get('/dashboard?window=3d');

        $response->assertOk();
        $response->assertSee('Within 3 days');
        $response->assertDontSee('Older than 3 days');
    }

    public function test_window_7d_includes_jobs_within_7_days_excludes_older(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user, now()->subDays(6), 'Within 7 days');
        $this->makeJob($user, now()->subDays(10), 'Older than 7 days');

        $response = $this->actingAs($user)->get('/dashboard?window=7d');

        $response->assertOk();
        $response->assertSee('Within 7 days');
        $response->assertDontSee('Older than 7 days');
    }

    public function test_window_all_includes_everything(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user, now()->subDays(2), 'Recent');
        $this->makeJob($user, now()->subDays(60), 'Ancient job');

        $response = $this->actingAs($user)->get('/dashboard?window=all');

        $response->assertOk();
        $response->assertSee('Recent');
        $response->assertSee('Ancient job');
    }

    public function test_stats_reflect_current_window(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user, now()->subHours(2), 'In window');
        $this->makeJob($user, now()->subDays(10), 'Out of window');

        $response = $this->actingAs($user)->get('/dashboard'); // default 24h

        $response->assertOk();
        // "jobs received" stat should count only the 1 in-window job
        $response->assertSeeInOrder(['<div class="n">1</div>', 'jobs received'], false);
    }

    public function test_invalid_window_falls_back_to_24h(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user, now()->subHours(2), 'In window');
        $this->makeJob($user, now()->subHours(30), 'Out of window');

        $response = $this->actingAs($user)->get('/dashboard?window=bogus');

        $response->assertOk();
        $response->assertSee('In window');
        $response->assertDontSee('Out of window');
    }

    public function test_status_filter_still_works_alongside_window(): void
    {
        $user = $this->makeUser();
        $skipped = $this->makeJob($user, now()->subHours(1), 'Skipped job');
        $skipped->update(['status' => 'skipped']);
        $this->makeJob($user, now()->subHours(1), 'Received job');

        $response = $this->actingAs($user)->get('/dashboard?window=24h&status=skipped');

        $response->assertOk();
        $response->assertSee('Skipped job');
        $response->assertDontSee('Received job');
    }
}
