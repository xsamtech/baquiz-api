<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clashs') && ! Schema::hasColumn('clashs', 'is_competition')) {
            Schema::table('clashs', function (Blueprint $table): void {
                $table->boolean('is_competition')->default(false)->after('currency');
            });
        }

        if (Schema::hasTable('medal_user') && ! Schema::hasColumn('medal_user', 'clash_id')) {
            Schema::table('medal_user', function (Blueprint $table): void {
                $table->foreignId('clash_id')->nullable()->constrained('clashs')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('medal_user') && Schema::hasColumn('medal_user', 'clash_id')) {
            Schema::table('medal_user', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('clash_id');
            });
        }

        if (Schema::hasTable('clashs') && Schema::hasColumn('clashs', 'is_competition')) {
            Schema::table('clashs', function (Blueprint $table): void {
                $table->dropColumn('is_competition');
            });
        }
    }
};
