<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('X-localization');

        if (in_array($locale, ['fr', 'en', 'ln'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
