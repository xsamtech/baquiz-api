<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'uuid')) {
                $table->uuid('uuid')->nullable()->unique();
            }

            if (! Schema::hasColumn('users', 'about')) {
                $table->text('about')->nullable();
            }

            if (! Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname')->nullable();
                $table->string('lastname')->nullable();
                $table->string('surname')->nullable();
                $table->string('username')->nullable()->unique();
                $table->string('phone', 45)->nullable()->unique();
                $table->text('avatar_url')->nullable();
                $table->text('api_token')->nullable();
            }

            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->json('role_name');
            $table->json('role_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_selected')->default(false);
            $table->timestamps();
            $table->unique(['role_id', 'user_id']);
        });

        Schema::create('websites', function (Blueprint $table): void {
            $table->id();
            $table->string('website_name');
            $table->text('website_url');
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
