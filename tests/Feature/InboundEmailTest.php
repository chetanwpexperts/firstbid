<?php

namespace Tests\Feature;

use App\Jobs\ProcessIncomingJob;
use App\Models\InboundEmail;
use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class InboundEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.inbound_email.secret' => 'test-secret']);
    }

    public function test_wrong_secret_returns_401(): void
    {
        $response = $this->postJson('/api/inbound-email/wrong-secret', [
            'to' => 'u_anything@mail.firstbidin.com',
            'from' => 'donotreply@upwork.com',
            'subject' => 'New jobs',
            'html' => '<p>hi</p>',
        ]);

        $response->assertStatus(401);
    }

    public function test_unknown_user_address_is_recorded_and_returns_ok(): void
    {
        $response = $this->postJson('/api/inbound-email/test-secret', [
            'to' => 'u_doesnotexist@mail.firstbidin.com',
            'from' => 'donotreply@upwork.com',
            'subject' => 'New jobs',
            'html' => '<p>hi</p>',
        ]);

        $response->assertOk()->assertJson(['status' => 'unknown_user']);

        $this->assertDatabaseHas('inbound_emails', [
            'to_address' => 'u_doesnotexist@mail.firstbidin.com',
            'status'     => 'unknown_user',
        ]);
    }

    public function test_gmail_verification_email_is_flagged_and_not_parsed_as_jobs(): void
    {
        Queue::fake();

        $user = User::factory()->create(['webhook_token' => Str::random(32)]);

        $response = $this->postJson('/api/inbound-email/test-secret', [
            'to' => "u_{$user->webhook_token}@mail.firstbidin.com",
            'from' => 'forwarding-noreply@google.com',
            'subject' => 'Forwarding Confirmation - forward-me@mail.firstbidin.com',
            'html' => '<p>Click here to confirm forwarding.</p>',
        ]);

        $response->assertOk()->assertJson(['status' => 'verification']);

        $this->assertDatabaseHas('inbound_emails', [
            'user_id' => $user->id,
            'status'  => 'verification',
        ]);

        $this->assertDatabaseCount('upwork_jobs', 0);
        Queue::assertNothingPushed();
    }

    public function test_fixture_email_with_two_jobs_creates_two_upwork_jobs_and_dispatches_processing(): void
    {
        Queue::fake();

        $user = User::factory()->create(['webhook_token' => Str::random(32)]);
        $html = file_get_contents(__DIR__ . '/../Fixtures/upwork_email.html');

        $response = $this->postJson('/api/inbound-email/test-secret', [
            'to' => "u_{$user->webhook_token}@mail.firstbidin.com",
            'from' => 'donotreply@upwork.com',
            'subject' => 'New jobs matching your search',
            'html' => $html,
        ]);

        $response->assertOk()->assertJson(['status' => 'parsed', 'count' => 2]);

        $this->assertDatabaseCount('upwork_jobs', 2);
        $this->assertSame(2, UpworkJob::where('user_id', $user->id)->where('source', 'email')->count());

        $jobs = UpworkJob::where('user_id', $user->id)->get();
        foreach ($jobs as $job) {
            $this->assertNull($job->uphunt_score);
            $this->assertTrue((bool) $job->payment_verified);
        }

        Queue::assertPushed(ProcessIncomingJob::class, 2);

        $inbound = InboundEmail::where('user_id', $user->id)->first();
        $this->assertSame('parsed', $inbound->status);
        $this->assertSame(2, $inbound->jobs_found);
    }

    public function test_duplicate_jobs_are_not_recreated_on_a_second_email(): void
    {
        Queue::fake();

        $user = User::factory()->create(['webhook_token' => Str::random(32)]);
        $html = file_get_contents(__DIR__ . '/../Fixtures/upwork_email.html');

        $payload = [
            'to' => "u_{$user->webhook_token}@mail.firstbidin.com",
            'from' => 'donotreply@upwork.com',
            'subject' => 'New jobs matching your search',
            'html' => $html,
        ];

        $this->postJson('/api/inbound-email/test-secret', $payload)->assertOk();
        $this->postJson('/api/inbound-email/test-secret', $payload)->assertOk();

        $this->assertDatabaseCount('upwork_jobs', 2);
    }
}
