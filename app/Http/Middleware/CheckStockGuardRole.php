<?php

namespace App\Http\Middleware;

use App\Enums\RoleType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStockGuardRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur a le rôle SCHOOL_GUARD
        if ($user->role && $user->role->name === RoleType::SCHOOL_GUARD) {
            return $next($request);
        }

        // Rediriger vers le dashboard si le rôle n'est pas SCHOOL_GUARD
        return redirect()->route('dashboard.main')
            ->with('error', 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette section.');
    }
}
