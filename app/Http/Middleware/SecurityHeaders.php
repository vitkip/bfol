<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    // CDN domains derived from actual layout files
    private const SCRIPT_SRCS = [
        "'self'",
        "'unsafe-inline'",          // admin Tailwind inline config + Alpine x-data attributes
        'https://cdn.tailwindcss.com',
        'https://cdn.jsdelivr.net',
        'https://cdnjs.cloudflare.com',
        'https://cdn.quilljs.com',
    ];

    private const STYLE_SRCS = [
        "'self'",
        "'unsafe-inline'",          // Tailwind utility classes injected at runtime
        'https://fonts.googleapis.com',
        'https://cdn.tailwindcss.com',
        'https://cdnjs.cloudflare.com',
        'https://cdn.quilljs.com',
    ];

    private const FONT_SRCS = [
        "'self'",
        'https://fonts.gstatic.com',
        'https://fonts.bunny.net',
        'https://cdnjs.cloudflare.com',
    ];

    private const FRAME_SRCS = [
        "'self'",
        'https://www.youtube.com',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isApi = $request->is('api/*');

        // Prevents browsers from interpreting files as a different MIME type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevents clickjacking via iframes from other origins
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Forces HTTPS for 1 year in production; skipped locally to avoid breaking dev HTTP
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Stops referrer leakage to third-party sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restricts browser features not needed by this app
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // CSP: API responses carry minimal policy; web responses carry full policy
        if ($isApi) {
            $response->headers->set('Content-Security-Policy', "default-src 'none'; frame-ancestors 'none'");
        } else {
            $scriptSrc = implode(' ', self::SCRIPT_SRCS);
            $styleSrc  = implode(' ', self::STYLE_SRCS);
            $fontSrc   = implode(' ', self::FONT_SRCS);
            $frameSrc  = implode(' ', self::FRAME_SRCS);

            $csp = implode('; ', [
                "default-src 'self'",
                "script-src {$scriptSrc}",
                "style-src {$styleSrc}",
                "font-src {$fontSrc}",
                "img-src 'self' data: blob: https:",   // https: covers partner/news external images
                "media-src 'self' blob:",
                "frame-src {$frameSrc}",
                "connect-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
