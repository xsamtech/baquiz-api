<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_endpoint_returns_the_user_websites_with_their_icon(): void
    {
        $user = User::factory()->create();
        $website = Website::query()->create([
            'website_name' => 'Baquiz',
            'website_url' => 'https://example.test',
            'logo_icon' => 'bi bi-globe2',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->getJson('/admin/account')
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('websites.0.id', $website->id)
            ->assertJsonPath('websites.0.logo_icon', 'bi bi-globe2');
    }
}
