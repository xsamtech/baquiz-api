<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            'logo_icon' => 'globe2',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->getJson('/admin/account')
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('websites.0.id', $website->id)
            ->assertJsonPath('websites.0.logo_icon', 'bi bi-globe2');
    }

    public function test_account_websites_can_be_synchronised(): void
    {
        $user = User::factory()->create();
        Website::query()->create([
            'website_name' => 'Old website',
            'website_url' => 'https://old.example.test',
            'logo_icon' => 'globe',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->putJson('/admin/account/websites', [
                'websites' => [[
                    'website_name' => 'Facebook',
                    'website_url' => 'https://facebook.com/baquiz',
                    'logo_icon' => 'facebook',
                ]],
            ])
            ->assertOk()
            ->assertJsonPath('websites.0.logo_icon', 'bi bi-facebook');

        $this->assertDatabaseMissing('websites', ['website_name' => 'Old website']);
        $this->assertDatabaseHas('websites', ['website_name' => 'Facebook', 'user_id' => $user->id]);
    }

    public function test_account_avatar_can_be_replaced_on_s3(): void
    {
        Storage::fake('s3');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/admin/account/avatar', ['avatar' => UploadedFile::fake()->image('avatar.jpg')])
            ->assertOk()
            ->assertJsonPath('user.id', $user->id);

        $this->assertNotNull($user->fresh()->avatar_url);
        $this->assertCount(1, Storage::disk('s3')->allFiles('baquiz/avatars'));
    }

    public function test_notifications_endpoint_paginates_all_notifications_when_requested(): void
    {
        $user = User::factory()->create();

        Notification::query()->create([
            'type' => 'welcome_new_user',
            'to_user_id' => $user->id,
            'is_read' => true,
        ]);

        Notification::query()->create([
            'type' => 'welcome_new_user',
            'to_user_id' => $user->id,
            'is_read' => false,
        ]);

        $this->actingAs($user)
            ->getJson('/admin/notifications')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.is_read', false);

        $this->actingAs($user)
            ->getJson('/admin/notifications?all=1')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 10);
    }
}
