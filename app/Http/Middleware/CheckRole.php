<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // super_admin tiene acceso total
        if ($user->is_super_admin) {
            return $next($request);
        }

        $institution = app('current_institution');

        if (! $institution) {
            return response()->json(['message' => 'Institución no establecida'], 400);
        }

        if ($user->hasRoleInInstitution('admin', $institution->id)) {
            return $next($request);
        }

        if ($user->hasAnyRoleInInstitution($roles, $institution->id)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'No tenés permisos para realizar esta acción',
        ], 403);
    }
}
