<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Role::query()->exists()) {
            Role::query()->create(['role_name' => ['fr' => 'Administrateur', 'en' => 'Administrator'], 'role_description' => ['fr' => 'Gestion des données de fonctionnement de la plateforme.', 'en' => 'Management of the platform operating data.']]);
            Role::query()->create(['role_name' => ['fr' => 'Partenaire', 'en' => 'Partner'], 'role_description' => ['fr' => 'Personne ou organisation qui finance la plateforme et/ou dont la plateforme met les annonces.', 'en' => 'Person or organization that finances the platform and/or whose advertisements are displayed on it.']]);
            Role::query()->create(['role_name' => ['fr' => 'Membre', 'en' => 'Member'], 'role_description' => ['fr' => 'Personne ou organisation qui utilise les fonctionnalités de la plateforme.', 'en' => 'Person or organization that uses the platform features.']]);
            Role::query()->create(['role_name' => ['fr' => 'Quiz master', 'en' => 'Quiz master'], 'role_description' => ['fr' => 'Membre qui a créé au moins une fois un clash sur la plateforme.', 'en' => 'Member who has created a clash on the platform at least once.']]);
        }

        return $next($request);
    }
}
