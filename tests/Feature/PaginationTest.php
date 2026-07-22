<?php

namespace Tests\Feature;

use App\Models\UpworkJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagination_renders_with_custom_partial_and_highlights_current_page(): void
    {
        $user = User::factory()->create();

        // 20 jobs, all within the default 24h window -> 2 pages at 15/page.
        for ($i = 1; $i <= 20; $i++) {
            UpworkJob::create([
                'user_id'        => $user->id,
                'status'         => 'received',
                'title'          => "Job {$i}",
                'description'    => 'desc',
                'budget_display' => '$50/hr',
                'raw_payload'    => [],
            ]);
        }

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('class="pager"', false);
        $response->assertSee('class="cur"', false); // current page marker present
        $response->assertSee('Next ›');
        // On page 1, "Prev" should be disabled (no href).
        $response->assertSee('<span class="disabled"', false);

        $page2 = $this->actingAs($user)->get('/dashboard?page=2');
        $page2->assertOk();
        $page2->assertSee('‹ Prev'); // now a real link, not disabled
    }

    public function test_pagination_absent_when_only_one_page(): void
    {
        $user = User::factory()->create();
        UpworkJob::create([
            'user_id' => $user->id, 'status' => 'received', 'title' => 'Only job',
            'description' => 'x', 'budget_display' => '$1', 'raw_payload' => [],
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertDontSee('class="pager"', false);
    }

    public function test_pagination_preserves_window_and_status_query_params(): void
    {
        $user = User::factory()->create();
        for ($i = 1; $i <= 20; $i++) {
            UpworkJob::create([
                'user_id' => $user->id, 'status' => 'received', 'title' => "Job {$i}",
                'description' => 'x', 'budget_display' => '$1', 'raw_payload' => [],
            ]);
        }

        $response = $this->actingAs($user)->get('/dashboard?window=7d');

        $response->assertOk();
        // withQueryString() should carry window= into the page 2 link
        $response->assertSee('window=7d', false);
    }
}
