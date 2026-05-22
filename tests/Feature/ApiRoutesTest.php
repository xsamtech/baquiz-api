<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiRoutesTest extends TestCase
{
    public function test_api_routes_are_registered(): void
    {
        $this->artisan('route:list', [
            '--path' => 'api/roles',
            '--except-vendor' => true,
        ])->assertSuccessful();
    }

    public function test_custom_api_routes_are_registered(): void
    {
        $this->artisan('route:list', [
            '--path' => 'api/users/login',
            '--except-vendor' => true,
        ])->assertSuccessful();

        $this->artisan('route:list', [
            '--path' => 'api/password-resets/check-token',
            '--except-vendor' => true,
        ])->assertSuccessful();

        $this->artisan('route:list', [
            '--path' => 'api/clashs/news-feed',
            '--except-vendor' => true,
        ])->assertSuccessful();

        $this->artisan('route:list', [
            '--path' => 'api/users/1/restore',
            '--except-vendor' => true,
        ])->assertSuccessful();
    }

    public function test_api_translation_messages_are_available(): void
    {
        app()->setLocale('fr');

        $this->assertSame('Connexion reussie.', __('api.users.login'));

        app()->setLocale('en');

        $this->assertSame('Login successful.', __('api.users.login'));
    }
}
