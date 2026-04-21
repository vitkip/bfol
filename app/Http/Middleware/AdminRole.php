<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            abort(403);
        }

        if (!in_array($user->role, ['superadmin', 'admin', 'editor', 'viewer'])) {
            abort(403);
        }

        return $next($request);
    }
}
