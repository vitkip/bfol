<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * $minRole optional parameter sets the minimum required role level.
     * Usage in routes: middleware('admin.role')           → viewer+
     *                  middleware('admin.role:editor')    → editor+
     *                  middleware('admin.role:admin')     → admin+
     *                  middleware('admin.role:superadmin')→ superadmin only
     */
    public function handle(Request $request, Closure $next, string $minRole = 'viewer'): Response
    {
        $user = $request->user();

        // Must be logged in and active
        if (!$user || !$user->is_active) {
            abort(403, 'ບັນຊີບໍ່ມີ ຫຼື ຖືກລະງັບ');
        }

        // Must have a recognised role
        if (!array_key_exists($user->role, \App\Models\User::ROLES)) {
            abort(403, 'Role ບໍ່ຖືກຕ້ອງ');
        }

        // Must meet the minimum role requirement for this route
        if (!$user->hasMinRole($minRole)) {
            abort(403, 'ທ່ານບໍ່ມີສິດເຂົ້າເຖິງໜ້ານີ້');
        }

        // Viewers are read-only — block any write operation
        if ($user->role === 'viewer' && !in_array($request->method(), ['GET', 'HEAD'])) {
            abort(403, 'Viewer ສາມາດເບິ່ງໄດ້ຢ່າງດຽວ');
        }

        return $next($request);
    }
}
