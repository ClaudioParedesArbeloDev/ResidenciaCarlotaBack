<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class SetActiveInstitution
{
    public function handle(Request $request, Closure $next): Response
    {
        $institutionId = $request->header('X-Institution-ID');

        if (! $institutionId) {
            return response()->json([
                'message' => 'Falta el header X-Institution-ID',
            ], 400);
        }

        $institution = Institution::where('id', $institutionId)
            ->where('is_active', true)
            ->first();

        if (! $institution) {
            return response()->json([
                'message' => 'Institución no encontrada o inactiva',
            ], 404);
        }

        $user = $request->user();

        if (! $user->belongsToInstitution($institution->id)) {
            return response()->json([
                'message' => 'No tenés acceso a esta institución',
            ], 403);
        }

       
        app()->instance('current_institution', $institution);

        return $next($request);
    }
}
