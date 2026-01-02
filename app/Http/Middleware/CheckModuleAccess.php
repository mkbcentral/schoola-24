<?php

namespace App\Http\Middleware;

use App\Services\Subscription\ModuleAccessService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    protected ModuleAccessService $moduleAccessService;

    public function __construct(ModuleAccessService $moduleAccessService)
    {
        $this->moduleAccessService = $moduleAccessService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleCode): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur a une école
        if (!$user->school) {
            abort(403, "Vous n'êtes pas associé à une école.");
        }

        // Vérifier l'accès au module
        if (!$this->moduleAccessService->canAccess($user->school, $moduleCode)) {
            // Rediriger vers une page d'information sur le module
            return redirect()
                ->route('school.modules.dashboard')
                ->with('error', "Votre école n'a pas accès au module demandé. Veuillez souscrire pour y accéder.");
        }

        // Vérifier si le module expire bientôt et afficher un avertissement
        if ($this->moduleAccessService->isExpiringSoon($user->school, $moduleCode)) {
            $daysRemaining = $this->moduleAccessService->getDaysRemaining($user->school, $moduleCode);
            session()->flash('warning', "Attention : Ce module expire dans {$daysRemaining} jour(s). Pensez à renouveler votre souscription.");
        }

        return $next($request);
    }
}
