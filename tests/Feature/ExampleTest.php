<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_reports_with_sqlite(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@enyaya.local')->firstOrFail();

        $response = $this->actingAs($admin)->get('/reports');

        $response->assertOk();
        $response->assertSee('Monthly Hearing Report');
    }
}
