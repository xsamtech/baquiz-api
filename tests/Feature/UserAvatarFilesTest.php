<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAvatarFilesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('phone_verfied_at')->nullable();
            $table->string('username')->nullable();
            $table->string('password');
            $table->string('api_key')->nullable();
            $table->string('avatar_url')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_resets', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('token');
            $table->string('former_password')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->nullable();
            $table->foreignId('from_user_id')->nullable();
            $table->foreignId('to_user_id')->nullable();
            $table->foreignId('message_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('files', function (Blueprint $table): void {
            $table->id();
            $table->string('file_name')->nullable();
            $table->string('file_url');
            $table->text('file_description')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->json('role_name');
            $table->json('role_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id');
            $table->foreignId('user_id');
            $table->boolean('is_selected')->default(false);
            $table->timestamps();
        });
    }

    public function test_store_saves_avatar_with_storage_and_sets_avatar_url(): void
    {
        Storage::fake('public');

        $response = $this->postJson('/api/users', [
            'firstname' => 'Ada',
            'password' => 'password',
            'confirm_password' => 'password',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertOk()
            ->assertJsonPath('data.users.firstname', 'Ada');

        $avatarUrl = $response->json('data.users.avatar_url');

        $this->assertIsString($avatarUrl);
        $this->assertStringContainsString('/storage/avatars/', $avatarUrl);
        Storage::disk('public')->assertExists(Str($avatarUrl)->after('/storage/')->toString());
        $this->assertDatabaseCount('files', 0);
    }

    public function test_store_creates_single_password_reset_and_member_role_when_email_and_phone_are_present(): void
    {
        $response = $this
            ->withHeader('X-localization', 'en')
            ->postJson('/api/users', [
                'firstname' => 'Linus',
                'email' => 'linus@example.com',
                'phone' => '+243810000000',
                'password' => 'password',
                'confirm_password' => 'password',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.users.roles.0.role_name', 'Member')
            ->assertJsonPath('data.password_resets.0.email', 'linus@example.com')
            ->assertJsonPath('data.password_resets.0.phone', '+243810000000');

        $this->assertDatabaseCount('password_resets', 1);
        $this->assertDatabaseHas('roles', [
            'role_name' => json_encode([
                'fr' => 'Membre',
                'en' => 'Member',
                'ln' => 'Membre',
            ]),
        ]);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $response->json('data.users.id'),
            'is_selected' => true,
        ]);
    }

    public function test_x_localization_header_changes_translatable_resource_locale(): void
    {
        $response = $this
            ->withHeader('X-localization', 'ln')
            ->postJson('/api/users', [
                'firstname' => 'Locale',
                'password' => 'password',
                'confirm_password' => 'password',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.users.roles.0.role_name', 'Membre')
            ->assertJsonPath('data.users.roles.0.role_description', 'Moto to ebongiseli oyo esalelaka plateforme');
    }

    public function test_login_allows_verified_email_but_blocks_unverified_phone(): void
    {
        $user = User::factory()->create([
            'email' => 'verified-email@example.com',
            'email_verified_at' => now(),
            'phone' => '+243810000001',
            'phone_verfied_at' => null,
        ]);

        $this->withHeader('X-localization', 'en')->postJson('/api/users/login', [
            'username' => $user->email,
            'password' => 'password',
        ])->assertOk();

        $this->withHeader('X-localization', 'en')->postJson('/api/users/login', [
            'username' => $user->phone,
            'password' => 'password',
        ])->assertForbidden()
            ->assertJsonPath('message', 'Please verify your phone number.');

        $this->assertDatabaseCount('password_resets', 0);
    }

    public function test_login_allows_verified_phone_but_blocks_unverified_email(): void
    {
        $user = User::factory()->create([
            'email' => 'unverified-email@example.com',
            'email_verified_at' => null,
            'phone' => '+243810000002',
            'phone_verfied_at' => now(),
        ]);

        $this->withHeader('X-localization', 'en')->postJson('/api/users/login', [
            'username' => $user->phone,
            'password' => 'password',
        ])->assertOk();

        $this->withHeader('X-localization', 'en')->postJson('/api/users/login', [
            'username' => $user->email,
            'password' => 'password',
        ])->assertForbidden()
            ->assertJsonPath('message', 'Please verify your email address.');

        $this->assertDatabaseCount('password_resets', 0);
    }

    public function test_update_avatar_saves_avatar_without_creating_file_record(): void
    {
        Storage::fake('public');
        $user = User::factory()->create([
            'firstname' => 'Grace',
            'avatar_url' => null,
        ]);

        $response = $this->postJson("/api/users/{$user->id}/avatar", [
            'avatar' => UploadedFile::fake()->image('new-avatar.png'),
        ]);

        $response->assertOk()
            ->assertJsonPath('data.firstname', 'Grace');

        $avatarUrl = $response->json('data.avatar_url');

        $this->assertIsString($avatarUrl);
        $this->assertStringContainsString('/storage/avatars/', $avatarUrl);
        Storage::disk('public')->assertExists(Str($avatarUrl)->after('/storage/')->toString());
        $this->assertDatabaseCount('files', 0);
    }

    public function test_add_files_creates_user_file_records(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson("/api/users/{$user->id}/files", [
            'files' => [
                [
                    'file_name' => 'identity',
                    'file_url' => 'https://example.com/id.png',
                    'file_description' => 'ID card',
                    'file_type' => 'id_card',
                ],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('data.0.file_name', 'identity')
            ->assertJsonPath('data.0.user_id', $user->id);

        $this->assertDatabaseHas('files', [
            'file_name' => 'identity',
            'file_url' => 'https://example.com/id.png',
            'file_type' => 'id_card',
            'user_id' => $user->id,
        ]);
    }
}
