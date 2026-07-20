<?php

namespace Tests\Feature;

use App\Models\InboundEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SettingsEmailPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_shows_the_personal_inbound_email_address(): void
    {
        $user = User::factory()->create(['webhook_token' => Str::random(32)]);

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertSee("u_{$user->webhook_token}@mail.firstbidin.com");
        $response->assertSee('No emails received yet');
        // existing webhook panel still present and unbroken
        $response->assertSee("/api/hook/{$user->webhook_token}");
    }

    public function test_verification_banner_shows_when_a_verification_email_exists(): void
    {
        $user = User::factory()->create(['webhook_token' => Str::random(32)]);
        InboundEmail::create([
            'user_id' => $user->id,
            'to_address' => "u_{$user->webhook_token}@mail.firstbidin.com",
            'from_address' => 'forwarding-noreply@google.com',
            'subject' => 'Forwarding Confirmation',
            'html' => '<p>Confirm forwarding</p>',
            'status' => 'verification',
        ]);

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertSee('Gmail sent a verification email');
    }

    public function test_verification_page_renders_stored_html(): void
    {
        $user = User::factory()->create(['webhook_token' => Str::random(32)]);
        InboundEmail::create([
            'user_id' => $user->id,
            'to_address' => "u_{$user->webhook_token}@mail.firstbidin.com",
            'from_address' => 'forwarding-noreply@google.com',
            'subject' => 'Forwarding Confirmation',
            'html' => '<p>Confirm forwarding link</p>',
            'status' => 'verification',
        ]);

        $response = $this->actingAs($user)->get('/settings/verification');

        $response->assertOk();
        $response->assertSee('srcdoc=', false);
    }

    public function test_verification_page_404s_when_no_verification_email_exists(): void
    {
        $user = User::factory()->create(['webhook_token' => Str::random(32)]);

        $response = $this->actingAs($user)->get('/settings/verification');

        $response->assertStatus(404);
    }
}
