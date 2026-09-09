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
        if (Schema::hasTable('websites') && ! Schema::hasColumn('websites', 'logo_icon')) {
            Schema::table('websites', function (Blueprint $table): void {
                $table->string('logo_icon')->nullable()->after('website_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('websites') && Schema::hasColumn('websites', 'logo_icon')) {
            Schema::table('websites', function (Blueprint $table): void {
                $table->dropColumn('logo_icon');
            });
        }
    }
};
