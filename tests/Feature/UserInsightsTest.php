<?php

namespace Tests\Feature;

use App\Models\History;
use App\Models\Medal;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserInsightsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-06-15 12:00:00'));

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('histories', function (Blueprint $table): void {
            $table->id();
            $table->string('word')->nullable();
            $table->string('entity')->nullable();
            $table->foreignId('entity_id')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable();
        });

        Schema::create('medals', function (Blueprint $table): void {
            $table->id();
            $table->string('medal_type');
            $table->string('medal_color');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
        });

        Schema::create('medal_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('medal_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_users_with_histories_by_period_returns_users_active_in_current_period(): void
    {
        $activeUser = User::factory()->create(['name' => 'Active User']);
        $oldUser = User::factory()->create(['name' => 'Old User']);
        $inactiveUser = User::factory()->create(['name' => 'Inactive User']);

        History::query()->create([
            'word' => 'dashboard',
            'action' => 'view',
            'user_id' => $activeUser->id,
        ])->forceFill([
            'created_at' => '2026-06-02 10:00:00',
            'updated_at' => '2026-06-02 10:00:00',
        ])->save();
        History::query()->create([
            'word' => 'archive',
            'action' => 'view',
            'user_id' => $oldUser->id,
        ])->forceFill([
            'created_at' => '2026-04-02 10:00:00',
            'updated_at' => '2026-04-02 10:00:00',
        ])->save();

        $response = $this->getJson('/api/users/histories/period?period=monthly');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $activeUser->id])
            ->assertJsonMissing(['id' => $oldUser->id])
            ->assertJsonMissing(['id' => $inactiveUser->id])
            ->assertJsonPath('data.0.histories.0.word', 'dashboard');
    }

    public function test_users_with_medals_returns_users_having_at_least_one_medal(): void
    {
        $medaledUser = User::factory()->create();
        $otherMedaledUser = User::factory()->create();
        $userWithoutMedal = User::factory()->create();
        $medal = Medal::query()->create([
            'medal_type' => 'elite',
            'medal_color' => 'gold',
        ]);

        $medal->users()->attach([$medaledUser->id, $otherMedaledUser->id]);

        $response = $this->getJson('/api/users/medals');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $medaledUser->id])
            ->assertJsonFragment(['id' => $otherMedaledUser->id])
            ->assertJsonMissing(['id' => $userWithoutMedal->id])
            ->assertJsonPath('data.0.medals.0.medal_type', 'elite');
    }

    public function test_pollchoice_translation_messages_are_available(): void
    {
        app()->setLocale('fr');

        $this->assertSame('Choix de sondage créé avec succès.', __('api.pollchoices.store'));

        app()->setLocale('en');

        $this->assertSame('Poll choice created successfully.', __('api.pollchoices.store'));
    }
}
