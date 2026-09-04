<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::creating(function (Model $model): void {
            static $tablesWithUuid = [];

            $table = $model->getTable();

            if (! array_key_exists($table, $tablesWithUuid)) {
                $tablesWithUuid[$table] = Schema::hasColumn($table, 'uuid');
            }

            if ($tablesWithUuid[$table] && blank($model->getAttribute('uuid'))) {
                $model->setAttribute('uuid', (string) Str::uuid());
            }
        });
    }
}
