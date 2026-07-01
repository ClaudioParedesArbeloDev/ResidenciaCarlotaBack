<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->is_super_admin) {
            return response()->json([
                'message' => 'Acceso restringido a super administradores',
            ], 403);
        }

        return $next($request);
    }
}
