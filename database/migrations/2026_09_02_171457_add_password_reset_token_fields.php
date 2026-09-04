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
        Schema::table('password_reset_tokens', function (Blueprint $table): void {
            if (! Schema::hasColumn('password_reset_tokens', 'phone')) {
                $table->string('phone', 45)->nullable()->index();
            }

            if (! Schema::hasColumn('password_reset_tokens', 'former_password')) {
                $table->text('former_password')->nullable();
            }

            if (! Schema::hasColumn('password_reset_tokens', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table): void {
            if (Schema::hasColumn('password_reset_tokens', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            if (Schema::hasColumn('password_reset_tokens', 'former_password')) {
                $table->dropColumn('former_password');
            }

            if (Schema::hasColumn('password_reset_tokens', 'phone')) {
                $table->dropIndex(['phone']);
                $table->dropColumn('phone');
            }
        });
    }
};
