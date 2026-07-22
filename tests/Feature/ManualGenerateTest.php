<?php

namespace Tests\Feature;

use App\Models\UpworkJob;
use App\Models\User;
use App\Services\ProposalAI;
use App\Services\TelegramNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualGenerateTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'plan'             => 'pro',
            'proposal_profile' => str_repeat('x', 120),
            'telegram_chat_id' => 'chat123',
            'letters_used'     => 0,
            'letters_quota'    => 15,
            'quota_reset_at'   => now()->addMonth(),
        ], $attrs));
    }

    private function makeJob(User $user, array $attrs = []): UpworkJob
    {
        return UpworkJob::create(array_merge([
            'user_id'          => $user->id,
            'status'           => 'ready_to_generate',
            'title'            => 'Some job',
            'description'      => 'desc',
            'budget_display'   => '$50/hr',
            'payment_verified' => true,
            'uphunt_score'     => 8,
            'raw_payload'      => [],
        ], $attrs));
    }

    public function test_generate_creates_letter_and_increments_quota(): void
    {
        $user = $this->makeUser();
        $job = $this->makeJob($user);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldReceive('generate')->once()->andReturn([
                'cover_letter'     => 'The letter text.',
                'question_answers' => [],
                'bid_suggestion'   => '$40/hr',
            ]);
        });
        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldReceive('sendJobAlert')->once();
        });

        $response = $this->actingAs($user)->post(route('jobs.generate', $job->id));

        $response->assertRedirect(route('jobs.show', $job->id));

        $job->refresh();
        $user->refresh();

        $this->assertSame('The letter text.', $job->cover_letter);
        $this->assertSame('notified', $job->status);
        $this->assertSame(1, $user->letters_used);
    }

    public function test_generate_twice_does_not_double_generate(): void
    {
        $user = $this->makeUser();
        $job = $this->makeJob($user, ['cover_letter' => 'Already have one']);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldNotReceive('generate');
        });

        $response = $this->actingAs($user)->post(route('jobs.generate', $job->id));

        $response->assertRedirect(route('jobs.show', $job->id));
        $this->assertSame(0, $user->refresh()->letters_used);
    }

    public function test_generate_blocked_when_trial_ended_and_not_pro(): void
    {
        $user = $this->makeUser(['plan' => 'free', 'trial_ends_at' => now()->subDay()]);
        $job = $this->makeJob($user);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldNotReceive('generate');
        });

        $response = $this->actingAs($user)->post(route('jobs.generate', $job->id));

        $response->assertSessionHasErrors('generate');
        $this->assertNull($job->refresh()->cover_letter);
    }

    public function test_generate_blocked_when_quota_exhausted(): void
    {
        $user = $this->makeUser(['letters_used' => 15, 'letters_quota' => 15]);
        $job = $this->makeJob($user);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldNotReceive('generate');
        });

        $response = $this->actingAs($user)->post(route('jobs.generate', $job->id));

        $response->assertSessionHasErrors('generate');
        $this->assertNull($job->refresh()->cover_letter);
    }

    public function test_generate_scoped_to_authenticated_user(): void
    {
        $owner = $this->makeUser();
        $intruder = $this->makeUser();
        $job = $this->makeJob($owner);

        $response = $this->actingAs($intruder)->post(route('jobs.generate', $job->id));

        $response->assertStatus(404);
    }

    public function test_job_detail_page_shows_generate_button_when_no_letter(): void
    {
        $user = $this->makeUser();
        $job = $this->makeJob($user);

        $response = $this->actingAs($user)->get(route('jobs.show', $job->id));

        $response->assertOk();
        $response->assertSee('Generate cover letter');
        $response->assertSee('ready', false); // status_label for ready_to_generate
    }

    public function test_job_detail_page_hides_generate_button_once_letter_exists(): void
    {
        $user = $this->makeUser();
        $job = $this->makeJob($user, ['cover_letter' => 'Already written', 'status' => 'notified']);

        $response = $this->actingAs($user)->get(route('jobs.show', $job->id));

        $response->assertOk();
        $response->assertDontSee('Generate cover letter');
        $response->assertSee('Already written');
    }

    public function test_dashboard_list_shows_generate_link_for_ready_to_generate_jobs(): void
    {
        $user = $this->makeUser();
        $this->makeJob($user);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Generate letter');
    }
}
