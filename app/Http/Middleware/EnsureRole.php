<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Non authentifié.');
        }

        foreach ($roles as $role) {
            if ($role === 'client' && $user->role === 'client') {
                return $next($request);
            }

            if ($role === 'agent' && $user->role === 'agent') {
                return $next($request);
            }

            if ($role === 'agent_general' && $user->role === 'agent' && $user->agent?->type_agent === 'general') {
                return $next($request);
            }

            if ($role === 'agent_specifique' && $user->role === 'agent' && $user->agent?->type_agent === 'specifique') {
                return $next($request);
            }

            if ($role === 'admin' && $user->role === 'admin') {
                return $next($request);
            }
        }

        abort(403, 'Accès non autorisé pour ce rôle.');
    }
}