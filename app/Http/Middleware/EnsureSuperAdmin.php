<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Autorise uniquement le SuperAdmin (role_id = 1).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || (int) Auth::user()->role_id !== 1) {
            abort(403, "Accès réservé au Super Administrateur.");
        }

        return $next($request);
    }
}
