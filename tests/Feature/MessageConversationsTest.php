<?php

namespace Tests\Feature;

use App\Models\Circle;
use App\Models\Message;
use App\Models\Pollchoice;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MessageConversationsTest extends TestCase
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('circles', function (Blueprint $table): void {
            $table->id();
            $table->string('circle_name');
            $table->string('profile_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable();
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->longText('message_content')->nullable();
            $table->text('event_title')->nullable();
            $table->longText('event_description')->nullable();
            $table->dateTime('event_start_at')->nullable();
            $table->dateTime('event_end_at')->nullable();
            $table->text('event_place')->nullable();
            $table->foreignId('answered_for')->nullable();
            $table->string('type')->nullable();
            $table->string('call_type')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('addressee_user_id')->nullable();
            $table->foreignId('addressee_circle_id')->nullable();
        });

        Schema::create('hashtags', function (Blueprint $table): void {
            $table->id();
            $table->string('keyword');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hashtag_message', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hashtag_id');
            $table->foreignId('message_id');
            $table->timestamps();
        });

        Schema::create('circle_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('circle_id');
            $table->foreignId('user_id');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });

        Schema::create('pollchoices', function (Blueprint $table): void {
            $table->id();
            $table->text('choice_content')->nullable();
            $table->text('image_url')->nullable();
            $table->timestamps();
            $table->foreignId('message_id')->nullable();
        });

        Schema::create('pollchoice_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pollchoice_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    public function test_private_conversation_returns_messages_from_both_users(): void
    {
        $user = User::factory()->create();
        $addressee = User::factory()->create();
        $outsideUser = User::factory()->create();

        Message::query()->create([
            'message_content' => 'Bonjour projet',
            'type' => 'text',
            'status' => 'read',
            'user_id' => $user->id,
            'addressee_user_id' => $addressee->id,
        ]);
        Message::query()->create([
            'message_content' => 'Reponse importante',
            'type' => 'text',
            'status' => 'unread',
            'user_id' => $addressee->id,
            'addressee_user_id' => $user->id,
        ]);
        Message::query()->create([
            'message_content' => 'Hors conversation',
            'type' => 'text',
            'user_id' => $user->id,
            'addressee_user_id' => $outsideUser->id,
        ]);

        $response = $this->getJson("/api/messages/conversations/users/{$user->id}/{$addressee->id}");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['message_content' => 'Bonjour projet'])
            ->assertJsonFragment(['message_content' => 'Reponse importante'])
            ->assertJsonMissing(['message_content' => 'Hors conversation']);
    }

    public function test_message_json_includes_poll_choices_with_users(): void
    {
        $user = User::factory()->create();
        $addressee = User::factory()->create();
        $voter = User::factory()->create();
        $message = Message::query()->create([
            'message_content' => 'Choisissez une option',
            'type' => 'poll',
            'user_id' => $user->id,
            'addressee_user_id' => $addressee->id,
        ]);
        $pollchoice = Pollchoice::query()->create([
            'choice_content' => 'Option A',
            'message_id' => $message->id,
        ]);

        $pollchoice->users()->attach($voter->id);

        $response = $this->getJson("/api/messages/conversations/users/{$user->id}/{$addressee->id}");

        $response->assertOk()
            ->assertJsonPath('data.0.poll_choices.0.choice_content', 'Option A')
            ->assertJsonPath('data.0.poll_choices.0.users.0.id', $voter->id);
    }

    public function test_store_creates_poll_message_with_choices(): void
    {
        $user = User::factory()->create();
        $addressee = User::factory()->create();

        $response = $this->postJson('/api/messages', [
            'message_content' => 'Votre choix ?',
            'type' => 'poll',
            'user_id' => $user->id,
            'addressee_user_id' => $addressee->id,
            'poll_choices' => [
                ['choice_content' => 'Option A'],
                ['choice_content' => 'Option B', 'image_url' => 'https://example.com/b.png'],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('data.type', 'poll')
            ->assertJsonCount(2, 'data.poll_choices')
            ->assertJsonPath('data.poll_choices.0.choice_content', 'Option A')
            ->assertJsonPath('data.poll_choices.1.image_url', 'https://example.com/b.png');

        $this->assertDatabaseHas('pollchoices', [
            'choice_content' => 'Option A',
            'message_id' => $response->json('data.id'),
        ]);
    }

    public function test_pollchoice_crud_can_manage_choices_and_users(): void
    {
        $user = User::factory()->create();
        $voter = User::factory()->create();
        $message = Message::query()->create([
            'message_content' => 'Sondage',
            'type' => 'poll',
            'user_id' => $user->id,
        ]);

        $storeResponse = $this->postJson('/api/pollchoices', [
            'choice_content' => 'Premier choix',
            'message_id' => $message->id,
            'user_ids' => [$voter->id],
        ]);

        $storeResponse->assertOk()
            ->assertJsonPath('data.choice_content', 'Premier choix')
            ->assertJsonPath('data.users.0.id', $voter->id);

        $pollchoiceId = $storeResponse->json('data.id');
        $updateResponse = $this->patchJson("/api/pollchoices/{$pollchoiceId}", [
            'choice_content' => 'Choix modifie',
            'user_ids' => [],
        ]);

        $updateResponse->assertOk()
            ->assertJsonPath('data.choice_content', 'Choix modifie')
            ->assertJsonCount(0, 'data.users');

        $this->getJson("/api/pollchoices/{$pollchoiceId}")
            ->assertOk()
            ->assertJsonPath('data.id', $pollchoiceId);

        $this->deleteJson("/api/pollchoices/{$pollchoiceId}")
            ->assertOk();

        $this->assertDatabaseMissing('pollchoices', [
            'id' => $pollchoiceId,
        ]);
    }

    public function test_user_conversations_are_grouped_like_chat_threads(): void
    {
        $user = User::factory()->create();
        $addressee = User::factory()->create();
        $member = User::factory()->create();
        $circle = Circle::query()->create([
            'circle_name' => 'Equipe',
            'user_id' => $user->id,
        ]);

        $circle->members()->attach([$user->id, $member->id]);
        Message::query()->create([
            'message_content' => 'Message prive',
            'type' => 'text',
            'user_id' => $user->id,
            'addressee_user_id' => $addressee->id,
        ]);
        Message::query()->create([
            'message_content' => 'Message cercle',
            'type' => 'text',
            'status' => 'unread',
            'user_id' => $member->id,
            'addressee_circle_id' => $circle->id,
        ]);

        $response = $this->getJson("/api/messages/conversations/{$user->id}");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['type' => 'user', 'addressee_user_id' => $addressee->id])
            ->assertJsonFragment(['type' => 'circle', 'addressee_circle_id' => $circle->id])
            ->assertJsonFragment(['unread_count' => 1]);
    }

    public function test_user_message_search_matches_content_and_event_fields(): void
    {
        $user = User::factory()->create();
        $addressee = User::factory()->create();

        Message::query()->create([
            'message_content' => 'Texte sans correspondance',
            'type' => 'text',
            'user_id' => $user->id,
            'addressee_user_id' => $addressee->id,
        ]);
        Message::query()->create([
            'message_content' => 'Invitation',
            'event_title' => 'Reunion pedagogique',
            'type' => 'event',
            'user_id' => $addressee->id,
            'addressee_user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/messages/search?user_id={$user->id}&q=pedagogique");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['event_title' => 'Reunion pedagogique'])
            ->assertJsonMissing(['message_content' => 'Texte sans correspondance']);
    }
}
