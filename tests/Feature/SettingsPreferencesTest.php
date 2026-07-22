<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SettingsPreferencesTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'webhook_token' => Str::random(32),
        ], $attrs));
    }

    public function test_settings_page_renders_the_new_controls(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/settings');

        $response->assertOk();
        $response->assertSee('name="min_score_operator"', false);
        $response->assertSee('name="auto_generate"', false);
        $response->assertSee('name="skip_unverified_payment"', false);
        // existing profile field still present
        $response->assertSee('name="proposal_profile"', false);
    }

    public function test_saving_settings_persists_new_fields(): void
    {
        $user = $this->makeUser([
            'auto_generate'           => false,
            'skip_unverified_payment' => true,
            'min_score_operator'      => '>=',
        ]);

        $response = $this->actingAs($user)->post(route('settings.update'), [
            'proposal_profile'        => str_repeat('x', 120),
            'min_score'               => 8,
            'min_score_operator'      => '>',
            'auto_generate'           => '1',
            // skip_unverified_payment intentionally omitted — an unchecked
            // checkbox sends nothing, must be saved as false.
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('ok');

        $user->refresh();

        $this->assertSame(8, $user->min_score);
        $this->assertSame('>', $user->min_score_operator);
        $this->assertTrue($user->auto_generate);
        $this->assertFalse($user->skip_unverified_payment);
    }

    public function test_unchecking_auto_generate_turns_it_back_off(): void
    {
        $user = $this->makeUser(['auto_generate' => true]);

        $this->actingAs($user)->post(route('settings.update'), [
            'proposal_profile'   => str_repeat('x', 120),
            'min_score'          => 7,
            'min_score_operator' => '>=',
            // auto_generate omitted -> unchecked
            'skip_unverified_payment' => '1',
        ]);

        $this->assertFalse($user->refresh()->auto_generate);
        $this->assertTrue($user->refresh()->skip_unverified_payment);
    }
}
