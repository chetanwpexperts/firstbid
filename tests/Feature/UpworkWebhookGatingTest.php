<?php

namespace Tests\Feature;

use App\Jobs\ProcessIncomingJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpworkWebhookGatingTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return [
            'platform' => 'upwork',
            'ciphertext' => '~test123',
            'title' => 'Some job',
            'description' => 'Job description',
            'matchingScore' => 8,
        ];
    }

    public function test_webhook_is_skipped_for_free_user_past_trial(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'webhook_token' => Str::random(32),
            'plan' => 'free',
            'trial_ends_at' => now()->subDay(),
        ]);

        $response = $this->postJson("/api/hook/{$user->webhook_token}", $this->payload());

        $response->assertOk();

        $this->assertDatabaseHas('upwork_jobs', [
            'user_id' => $user->id,
            'status' => 'skipped',
            'skip_reason' => 'webhook is a Pro feature',
        ]);

        Queue::assertNothingPushed();
    }

    public function test_webhook_works_during_trial(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'webhook_token' => Str::random(32),
            'plan' => 'free',
            'trial_ends_at' => now()->addDays(10),
        ]);

        $response = $this->postJson("/api/hook/{$user->webhook_token}", $this->payload());

        $response->assertOk();

        $this->assertDatabaseHas('upwork_jobs', [
            'user_id' => $user->id,
            'status' => 'received',
        ]);

        Queue::assertPushed(ProcessIncomingJob::class, 1);
    }

    public function test_webhook_works_for_pro_plan_after_trial(): void
    {
        Queue::fake();

        $user = User::factory()->create([
            'webhook_token' => Str::random(32),
            'plan' => 'pro',
            'trial_ends_at' => now()->subDay(),
        ]);

        $response = $this->postJson("/api/hook/{$user->webhook_token}", $this->payload());

        $response->assertOk();

        $this->assertDatabaseHas('upwork_jobs', [
            'user_id' => $user->id,
            'status' => 'received',
        ]);

        Queue::assertPushed(ProcessIncomingJob::class, 1);
    }
}
