<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Assertion;
use App\Models\Clash;
use App\Models\Comment;
use App\Models\Hashtag;
use App\Models\Message;
use App\Models\Question;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HashtagEntitiesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('hashtags', function (Blueprint $table): void {
            $table->id();
            $table->string('keyword');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('clashs', function (Blueprint $table): void {
            $table->id();
            $table->text('clash_description')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->text('comment_content')->nullable();
            $table->foreignId('answered_for')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->text('message_content')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('questions', function (Blueprint $table): void {
            $table->id();
            $table->text('question_content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('assertions', function (Blueprint $table): void {
            $table->id();
            $table->text('assertion_content')->nullable();
            $table->boolean('is_real_answer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('answers', function (Blueprint $table): void {
            $table->id();
            $table->text('answer_content')->nullable();
            $table->integer('time_taken')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        foreach (['clash', 'comment', 'message', 'question', 'assertion', 'answer'] as $entity) {
            Schema::create("hashtag_{$entity}", function (Blueprint $table) use ($entity): void {
                $table->id();
                $table->foreignId('hashtag_id');
                $table->foreignId("{$entity}_id");
                $table->timestamps();
            });
        }

        Schema::create('pollchoices', function (Blueprint $table): void {
            $table->id();
            $table->text('choice_content')->nullable();
            $table->foreignId('message_id')->nullable();
            $table->timestamps();
        });

        Schema::create('pollchoice_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pollchoice_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    public function test_entities_returns_all_hashtag_related_entity_branches(): void
    {
        $hashtag = Hashtag::query()->create(['keyword' => 'science']);
        $clash = Clash::query()->create(['clash_description' => 'Clash science']);
        $comment = Comment::query()->create(['comment_content' => 'Comment science']);
        $message = Message::query()->create(['message_content' => 'Message science']);
        $question = Question::query()->create(['question_content' => 'Question science']);
        $assertion = Assertion::query()->create(['assertion_content' => 'Assertion science']);
        $answer = Answer::query()->create(['answer_content' => 'Answer science']);

        $hashtag->clashs()->attach($clash->id);
        $hashtag->comments()->attach($comment->id);
        $hashtag->messages()->attach($message->id);
        $hashtag->questions()->attach($question->id);
        $hashtag->assertions()->attach($assertion->id);
        $hashtag->answers()->attach($answer->id);

        $response = $this->getJson('/api/hashtags/science/entities');

        $response->assertOk()
            ->assertJsonPath('data.keyword', 'science')
            ->assertJsonPath('data.clashs.0.clash_description', 'Clash science')
            ->assertJsonPath('data.comments.0.comment_content', 'Comment science')
            ->assertJsonPath('data.messages.0.message_content', 'Message science')
            ->assertJsonPath('data.questions.0.question_content', 'Question science')
            ->assertJsonPath('data.assertions.0.assertion_content', 'Assertion science')
            ->assertJsonPath('data.answers.0.answer_content', 'Answer science');
    }
}
