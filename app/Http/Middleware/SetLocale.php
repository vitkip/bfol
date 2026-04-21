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
        $locale = $request->segment(1);

        if (in_array($locale, self::SUPPORTED)) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        } else {
            app()->setLocale(session('locale', config('app.locale')));
        }

        return $next($request);
    }
}
