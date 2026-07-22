<?php

namespace Tests\Feature;

use App\Jobs\ProcessIncomingJob;
use App\Models\UpworkJob;
use App\Models\User;
use App\Services\ProposalAI;
use App\Services\TelegramNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessIncomingJobCaptureTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'plan'              => 'pro', // sidesteps trial logic for these tests
            'proposal_profile'  => str_repeat('x', 120),
            'telegram_chat_id'  => 'chat123',
            'min_score'         => 7,
            'letters_used'      => 0,
            'letters_quota'     => 15,
            'quota_reset_at'    => now()->addMonth(),
        ], $attrs));
    }

    private function makeJob(User $user, array $attrs = []): UpworkJob
    {
        return UpworkJob::create(array_merge([
            'user_id'          => $user->id,
            'status'           => 'received',
            'title'            => 'Some job',
            'description'      => 'desc',
            'budget_display'   => '$50/hr',
            'payment_verified' => true,
            'uphunt_score'     => 8,
            'raw_payload'      => [],
        ], $attrs));
    }

    public function test_default_auto_generate_false_captures_job_without_generating(): void
    {
        $user = $this->makeUser(['auto_generate' => false]);
        $job = $this->makeJob($user);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldNotReceive('generate');
        });
        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldReceive('sendJobCaptured')->once();
            $mock->shouldNotReceive('sendJobAlert');
        });

        ProcessIncomingJob::dispatch($job->id);

        $job->refresh();
        $user->refresh();

        $this->assertSame('ready_to_generate', $job->status);
        $this->assertNull($job->cover_letter);
        $this->assertSame(0, $user->letters_used);
    }

    public function test_auto_generate_true_keeps_existing_generate_and_notify_behavior(): void
    {
        $user = $this->makeUser(['auto_generate' => true]);
        $job = $this->makeJob($user);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldReceive('generate')->once()->andReturn([
                'cover_letter'     => 'Hello, this is the letter.',
                'question_answers' => [],
                'bid_suggestion'   => '$40/hr',
            ]);
        });
        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldReceive('sendJobAlert')->once();
            $mock->shouldNotReceive('sendJobCaptured');
        });

        ProcessIncomingJob::dispatch($job->id);

        $job->refresh();
        $user->refresh();

        $this->assertSame('notified', $job->status);
        $this->assertSame('Hello, this is the letter.', $job->cover_letter);
        $this->assertSame(1, $user->letters_used);
    }

    public function test_skip_unverified_payment_true_skips_before_any_generation(): void
    {
        $user = $this->makeUser(['skip_unverified_payment' => true]);
        $job = $this->makeJob($user, ['payment_verified' => false]);

        $this->mock(ProposalAI::class, function ($mock) {
            $mock->shouldNotReceive('generate');
        });
        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldNotReceive('sendJobCaptured');
            $mock->shouldNotReceive('sendJobAlert');
        });

        ProcessIncomingJob::dispatch($job->id);

        $job->refresh();

        $this->assertSame('skipped', $job->status);
        $this->assertSame('payment not verified', $job->skip_reason);
    }

    public function test_skip_unverified_payment_false_lets_unverified_job_through(): void
    {
        $user = $this->makeUser(['skip_unverified_payment' => false, 'auto_generate' => false]);
        $job = $this->makeJob($user, ['payment_verified' => false]);

        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldReceive('sendJobCaptured')->once();
        });

        ProcessIncomingJob::dispatch($job->id);

        $this->assertSame('ready_to_generate', $job->refresh()->status);
    }

    public function test_operator_greater_than_skips_score_equal_to_min(): void
    {
        $user = $this->makeUser(['min_score' => 7, 'min_score_operator' => '>']);
        $job = $this->makeJob($user, ['uphunt_score' => 7]);

        ProcessIncomingJob::dispatch($job->id);

        $this->assertSame('skipped', $job->refresh()->status);
    }

    public function test_operator_greater_than_passes_score_above_min(): void
    {
        $user = $this->makeUser(['min_score' => 7, 'min_score_operator' => '>', 'auto_generate' => false]);
        $job = $this->makeJob($user, ['uphunt_score' => 8]);

        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldReceive('sendJobCaptured')->once();
        });

        ProcessIncomingJob::dispatch($job->id);

        $this->assertSame('ready_to_generate', $job->refresh()->status);
    }

    public function test_null_score_passes_the_score_filter_regardless_of_operator(): void
    {
        $user = $this->makeUser(['min_score' => 7, 'min_score_operator' => '>', 'auto_generate' => false]);
        $job = $this->makeJob($user, ['uphunt_score' => null, 'source' => 'email']);

        $this->mock(TelegramNotifier::class, function ($mock) {
            $mock->shouldReceive('sendJobCaptured')->once();
        });

        ProcessIncomingJob::dispatch($job->id);

        $this->assertSame('ready_to_generate', $job->refresh()->status);
    }
}
