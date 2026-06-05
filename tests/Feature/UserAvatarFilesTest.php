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
