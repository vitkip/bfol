<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['lo', 'en', 'zh'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'lo'));
        if (in_array($locale, self::SUPPORTED)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('app.locale', 'lo'));
        }

        return $next($request);
    }
}
