<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClashRoleSelectionTest extends TestCase
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
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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

        Schema::create('clashs', function (Blueprint $table): void {
            $table->id();
            $table->string('clash_code')->nullable();
            $table->text('clash_description')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->decimal('price')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('last_boost_at')->nullable();
            $table->string('boost_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('field_id')->nullable();
            $table->foreignId('user_id')->nullable();
        });

        Schema::create('hashtags', function (Blueprint $table): void {
            $table->id();
            $table->string('keyword');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hashtag_clash', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hashtag_id');
            $table->foreignId('clash_id');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->nullable();
            $table->foreignId('from_user_id')->nullable();
            $table->foreignId('to_user_id')->nullable();
            $table->foreignId('clash_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('follower_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_store_selects_quiz_master_role_for_non_admin_creator(): void
    {
        $user = User::factory()->create();
        $memberRole = Role::query()->create([
            'role_name' => ['fr' => 'Membre', 'en' => 'Member', 'ln' => 'Membre'],
            'role_description' => ['fr' => 'Membre'],
        ]);
        $user->roles()->attach($memberRole->id, ['is_selected' => true]);

        $response = $this->postJson('/api/clashs', [
            'clash_description' => 'Premier clash',
            'type' => 'public',
            'user_id' => $user->id,
        ]);

        $response->assertOk();

        $quizMasterRole = Role::query()
            ->whereJsonContainsLocale('role_name', 'fr', 'Quiz master')
            ->first();

        $this->assertNotNull($quizMasterRole);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $memberRole->id,
            'is_selected' => false,
        ]);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $quizMasterRole->id,
            'is_selected' => true,
        ]);
    }

    public function test_store_keeps_administrator_role_selected(): void
    {
        $user = User::factory()->create();
        $administratorRole = Role::query()->create([
            'role_name' => ['fr' => 'Administrateur', 'en' => 'Administrator', 'ln' => 'Administrateur'],
            'role_description' => ['fr' => 'Administrateur'],
        ]);
        $user->roles()->attach($administratorRole->id, ['is_selected' => true]);

        $response = $this->postJson('/api/clashs', [
            'clash_description' => 'Clash administrateur',
            'type' => 'public',
            'user_id' => $user->id,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $administratorRole->id,
            'is_selected' => true,
        ]);
        $this->assertDatabaseMissing('roles', [
            'role_name' => json_encode([
                'fr' => 'Quiz master',
                'en' => 'Quiz master',
                'ln' => 'Quiz master',
            ]),
        ]);
    }
}
