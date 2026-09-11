<?php

namespace Tests\Feature\Auth;

use App\Models\Notification;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'firstname' => 'Test',
            'lastname' => 'User',
            'surname' => 'Example',
            'email' => 'test@example.com',
            'username' => 'test-user',
            'country' => 'Democratic Republic of the Congo',
            'phone' => '+243810000000',
            'password' => 'Password!123',
            'password_confirmation' => 'Password!123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::query()->where('email', 'test@example.com')->firstOrFail();

        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $user->uuid);
        $this->assertSame('Democratic Republic of the Congo', $user->country);
        $this->assertTrue($user->roles()->whereJsonContainsLocale('role_name', 'fr', 'Administrateur')->exists());
        $this->assertMatchesRegularExpression('/^\d{6}$/', PasswordReset::query()->where('email', $user->email)->sole()->token);
        $this->assertTrue(Notification::query()->where('to_user_id', $user->id)->where('type', 'welcome_new_user')->where('is_read', false)->exists());
    }

    public function test_registration_is_forbidden_after_the_first_user_exists(): void
    {
        User::factory()->create();

        $this->get('/register')->assertForbidden();
    }

    public function test_registration_stores_an_avatar_on_s3(): void
    {
        Storage::fake('s3');

        $this->post('/register', [
            'firstname' => 'Avatar',
            'lastname' => 'User',
            'surname' => 'Example',
            'email' => 'avatar@example.com',
            'username' => 'avatar-user',
            'country' => 'Democratic Republic of the Congo',
            'phone' => '+243810000001',
            'password' => 'Password!123',
            'password_confirmation' => 'Password!123',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertNotNull(User::query()->where('email', 'avatar@example.com')->sole()->avatar_url);
        $this->assertCount(1, Storage::disk('s3')->allFiles('baquiz/avatars'));
    }

    public function test_roles_are_initialized_before_anyone_logs_in(): void
    {
        $this->get('/login')->assertOk();

        $this->assertSame(4, Role::query()->count());
        $this->assertSame('Administrateur', Role::query()->first()->getTranslation('role_name', 'fr'));
        $this->assertSame('Administrator', Role::query()->first()->getTranslation('role_name', 'en'));
    }
}
