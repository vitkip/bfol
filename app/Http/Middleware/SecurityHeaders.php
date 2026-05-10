<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies security headers to every HTTP response.
 *
 * Design principles:
 *  - Production: strictest possible — HSTS, COOP, CORP, upgrade-insecure-requests
 *  - Local dev:  relaxed only where required by tooling (localhost vs 127.0.0.1 URL mismatch)
 *  - API routes: minimal policy (no HTML, no embeds)
 *
 * CDN inventory (keep in sync with actual layout files):
 *  - scripts : cdn.tailwindcss.com, cdn.jsdelivr.net (TinyMCE + Alpine), cdnjs.cloudflare.com
 *  - styles  : fonts.googleapis.com, cdn.tailwindcss.com, cdnjs.cloudflare.com, cdn.jsdelivr.net
 *  - fonts   : fonts.gstatic.com, fonts.bunny.net, cdnjs.cloudflare.com
 *  - frames  : youtube.com, youtube-nocookie.com
 */
class SecurityHeaders
{
    /* ── Script sources ──────────────────────────────────────────────── */
    private const SCRIPT_SRCS = [
        "'self'",
        "'unsafe-inline'",          // Tailwind CDN inline config block + Alpine x-data attributes
        "'unsafe-eval'",            // Alpine.js v3 requires new Function() for expression evaluation
        'https://cdn.tailwindcss.com',
        'https://cdn.jsdelivr.net', // TinyMCE v7 + Alpine CDN
        'https://cdnjs.cloudflare.com',
    ];

    /* ── Style sources ───────────────────────────────────────────────── */
    private const STYLE_SRCS = [
        "'self'",
        "'unsafe-inline'",          // Tailwind utilities + TinyMCE runtime style injection
        'https://fonts.googleapis.com',
        'https://cdn.tailwindcss.com',
        'https://cdnjs.cloudflare.com',
        'https://cdn.jsdelivr.net',
    ];

    /* ── Font sources ────────────────────────────────────────────────── */
    private const FONT_SRCS = [
        "'self'",
        'https://fonts.gstatic.com',
        'https://fonts.bunny.net',
        'https://cdnjs.cloudflare.com',
        'https://cdn.jsdelivr.net',  // TinyMCE skin icon fonts (if any)
    ];

    /* ── Frame / embed sources ───────────────────────────────────────── */
    private const FRAME_SRCS = [
        "'self'",
        'https://www.youtube.com',
        'https://www.youtube-nocookie.com',
    ];

    /* ── Web worker sources (TinyMCE v7 spell-check worker) ──────────── */
    private const WORKER_SRCS = [
        "'self'",
        'blob:',
    ];

    /* ── Dev-only img hostnames — avoid wildcard http: in production ─── */
    private const DEV_IMG_HOSTS = [
        'http://localhost:*',
        'http://127.0.0.1:*',
    ];

    /* ── Dev-only connect hostnames (AJAX, fetch, XHR) ──────────────── */
    private const DEV_CONNECT_HOSTS = [
        'http://localhost:*',
        'http://127.0.0.1:*',
    ];

    /* ────────────────────────────────────────────────────────────────── */

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isProd = app()->isProduction();
        $isApi  = $request->is('api/*');

        /* ── Universal headers (all environments, all routes) ────────── */

        // Prevents MIME-sniffing attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Clickjacking protection (legacy fallback; CSP frame-ancestors is the primary control)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Stops referrer header leaking sensitive URL params to third parties
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Prevents Adobe Flash / PDF cross-domain requests
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Restricts powerful browser APIs to the minimum needed
        $response->headers->set('Permissions-Policy', implode(', ', [
            'accelerometer=()',
            'ambient-light-sensor=()',
            'autoplay=()',
            'battery=()',
            'camera=()',
            'cross-origin-isolated=()',
            'display-capture=()',
            'document-domain=()',
            'encrypted-media=()',
            'execution-while-not-rendered=()',
            'execution-while-out-of-viewport=()',
            'fullscreen=(self)',      // needed for TinyMCE fullscreen plugin
            'geolocation=()',
            'gyroscope=()',
            'magnetometer=()',
            'microphone=()',
            'midi=()',
            'navigation-override=()',
            'payment=()',
            'picture-in-picture=()',
            'publickey-credentials-get=()',
            'screen-wake-lock=()',
            'sync-xhr=()',
            'usb=()',
            'web-share=()',
            'xr-spatial-tracking=()',
        ]));

        /* ── Production-only headers ─────────────────────────────────── */

        if ($isProd) {
            // Forces HTTPS for 1 year; preload signals intent to join the HSTS preload list
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );

            // Prevents cross-origin window.opener attacks (popups, link targets)
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

            // Prevents this site's resources being embedded by other origins (Spectre mitigation)
            $response->headers->set('Cross-Origin-Resource-Policy', 'same-site');

            // Prevents this site being embedded in cross-origin browsing contexts
            $response->headers->set('Cross-Origin-Embedder-Policy', 'credentialless');
        }

        /* ── Content-Security-Policy ─────────────────────────────────── */

        if ($isApi) {
            // API responses carry no HTML — minimal policy
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'none'; frame-ancestors 'none'"
            );
        } else {
            $scriptSrc = implode(' ', self::SCRIPT_SRCS);
            $styleSrc  = implode(' ', self::STYLE_SRCS);
            $fontSrc   = implode(' ', self::FONT_SRCS);
            $frameSrc  = implode(' ', self::FRAME_SRCS);
            $workerSrc = implode(' ', self::WORKER_SRCS);

            // img-src: in local dev, allow both http://localhost and http://127.0.0.1
            // because APP_URL may differ from the browser address (localhost vs 127.0.0.1).
            // In production, https: is sufficient (all uploaded assets are served over HTTPS).
            $devHosts   = implode(' ', self::DEV_IMG_HOSTS);
            $imgSrc     = app()->isLocal()
                ? "'self' data: blob: https: {$devHosts}"
                : "'self' data: blob: https:";

            // connect-src: AJAX/fetch/XHR targets — dev adds both localhost variants
            $devConnect = implode(' ', self::DEV_CONNECT_HOSTS);
            $connectSrc = app()->isLocal()
                ? "'self' blob: {$devConnect}"
                : "'self' blob:";

            $directives = [
                "default-src 'self'",
                "script-src {$scriptSrc}",
                "style-src {$styleSrc}",
                "font-src {$fontSrc}",
                "img-src {$imgSrc}",
                "media-src 'self' blob:",
                "frame-src {$frameSrc}",
                "worker-src {$workerSrc}",
                "connect-src {$connectSrc}",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",  // supersedes X-Frame-Options in modern browsers
            ];

            // Force HTTP sub-resources to upgrade to HTTPS in production
            if ($isProd) {
                $directives[] = 'upgrade-insecure-requests';
            }

            $response->headers->set(
                'Content-Security-Policy',
                implode('; ', $directives)
            );
        }

        return $response;
    }
}
