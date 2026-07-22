<?php

namespace Tests\Feature;

use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationBellTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create($attrs);
    }

    private function makeJob(User $user, \Illuminate\Support\Carbon $createdAt, string $title): UpworkJob
    {
        $job = UpworkJob::create([
            'user_id' => $user->id, 'status' => 'received', 'title' => $title,
            'description' => 'x', 'budget_display' => '$1', 'raw_payload' => [],
        ]);
        $job->forceFill(['created_at' => $createdAt])->save();

        return $job;
    }

    public function test_badge_hidden_when_no_unseen_jobs(): void
    {
        $user = $this->makeUser(['last_seen_jobs_at' => now()]);

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertDontSee('id="notifBadge"', false);
    }

    public function test_badge_shows_correct_unseen_count(): void
    {
        $user = $this->makeUser(['last_seen_jobs_at' => now()->subHours(2)]);
        $this->makeJob($user, now()->subHours(1), 'New job 1');
        $this->makeJob($user, now()->subMinutes(30), 'New job 2');
        $this->makeJob($user, now()->subHours(5), 'Old, already seen');

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertSee('id="notifBadge"', false);
        $response->assertSeeInOrder(['notifBadge', '>2<'], false);
    }

    public function test_null_last_seen_falls_back_to_7_day_window(): void
    {
        $user = $this->makeUser(['last_seen_jobs_at' => null]);
        $this->makeJob($user, now()->subDays(2), 'Within fallback window');
        $this->makeJob($user, now()->subDays(10), 'Outside fallback window');

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertSeeInOrder(['notifBadge', '>1<'], false);
    }

    public function test_dropdown_lists_latest_unseen_jobs_with_link_to_job(): void
    {
        $user = $this->makeUser(['last_seen_jobs_at' => now()->subHours(1)]);
        $job = $this->makeJob($user, now()->subMinutes(10), 'Fresh job title');

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertSee('Fresh job title');
        $response->assertSee("/jobs/{$job->id}", false);
        $response->assertSee('View all jobs');
    }

    public function test_visiting_dashboard_resets_unseen_count(): void
    {
        $user = $this->makeUser(['last_seen_jobs_at' => now()->subDays(1)]);
        $this->makeJob($user, now()->subHours(2), 'Unseen job');

        // Before: unseen count should be 1.
        $before = $this->actingAs($user)->get('/settings');
        $before->assertSee('id="notifBadge"', false);

        // Visiting the dashboard marks everything seen.
        $this->actingAs($user)->get('/dashboard');

        $after = $this->actingAs($user)->get('/settings');
        $after->assertDontSee('id="notifBadge"', false);

        $this->assertNotNull($user->fresh()->last_seen_jobs_at);
    }

    public function test_mark_seen_endpoint_resets_unseen_count(): void
    {
        $user = $this->makeUser(['last_seen_jobs_at' => now()->subDays(1)]);
        $this->makeJob($user, now()->subHours(2), 'Unseen job');

        $before = $this->actingAs($user)->get('/settings');
        $before->assertSee('id="notifBadge"', false);

        $markSeen = $this->actingAs($user)->postJson(route('notifications.seen'));
        $markSeen->assertOk()->assertJson(['ok' => true]);

        $after = $this->actingAs($user)->get('/settings');
        $after->assertDontSee('id="notifBadge"', false);
    }

    public function test_guest_pages_render_without_error(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }
}
