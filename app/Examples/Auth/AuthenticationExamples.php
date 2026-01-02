<?php

/**
 * Exemples d'utilisation du module d'authentification V2
 *
 * Ces exemples montrent comment utiliser les différentes fonctionnalités
 * du système d'authentification dans votre application.
 */

namespace App\Examples\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Log;

class AuthenticationExamples
{
    private AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Exemple 1 : Connexion simple avec username
     */
    public function loginWithUsername(): array
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'john.doe',
            'password' => 'secret123',
            'remember' => false,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $result = $this->authService->login($loginDTO);

        if ($result['success']) {
            // Connexion réussie
            return [
                'status' => 'success',
                'user' => $result['user']->name,
                'message' => $result['message'],
            ];
        }

        // Connexion échouée
        return [
            'status' => 'error',
            'message' => $result['message'],
            'remaining_attempts' => $result['remainingAttempts'],
        ];
    }

    /**
     * Exemple 2 : Connexion avec email
     */
    public function loginWithEmail(): array
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'john.doe@example.com',
            'password' => 'secret123',
            'remember' => true, // Se souvenir de moi
        ]);

        return $this->authService->login($loginDTO);
    }

    /**
     * Exemple 3 : Vérifier si un utilisateur est bloqué avant de tenter la connexion
     */
    public function checkIfUserIsLocked(string $identifier): array
    {
        if ($this->authService->isLocked($identifier)) {
            $lockoutTime = $this->authService->getLockoutTime($identifier);
            $minutes = ceil($lockoutTime / 60);

            return [
                'locked' => true,
                'message' => "Compte bloqué. Veuillez réessayer dans {$minutes} minute(s).",
                'lockout_time' => $lockoutTime,
            ];
        }

        return [
            'locked' => false,
            'remaining_attempts' => $this->authService->getRemainingAttempts($identifier),
        ];
    }

    /**
     * Exemple 4 : Obtenir toutes les informations sur les tentatives
     */
    public function getLoginAttemptDetails(string $identifier): array
    {
        $info = $this->authService->getLoginAttemptInfo($identifier);

        return [
            'is_locked' => $info['isLocked'],
            'remaining_attempts' => $info['remainingAttempts'],
            'lockout_time_seconds' => $info['lockoutTime'],
            'lockout_time_minutes' => ceil($info['lockoutTime'] / 60),
            'max_attempts' => $info['maxAttempts'],
        ];
    }

    /**
     * Exemple 5 : Déconnexion simple
     */
    public function logout(): void
    {
        $this->authService->logout();

        // Optionnel : Message flash pour la prochaine page
        session()->flash('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Exemple 6 : Vérifier si l'utilisateur est authentifié
     */
    public function checkAuthentication(): array
    {
        if ($this->authService->isAuthenticated()) {
            $user = $this->authService->getCurrentUser();

            return [
                'authenticated' => true,
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'last_login' => $user->last_login_at?->format('Y-m-d H:i:s'),
            ];
        }

        return ['authenticated' => false];
    }

    /**
     * Exemple 7 : Réinitialiser les tentatives (Admin uniquement)
     */
    public function resetUserAttempts(string $identifier): void
    {
        // Vérifier que l'utilisateur actuel est admin
        if (!auth()->user()->role->name === 'admin') {
            throw new \Exception('Accès non autorisé');
        }

        $this->authService->resetLoginAttempts($identifier);

        Log::info("Tentatives réinitialisées pour {$identifier} par l'admin " . auth()->user()->username);
    }

    /**
     * Exemple 8 : Valider des credentials sans se connecter
     */
    public function validateCredentials(string $identifier, string $password): bool
    {
        return $this->authService->validateCredentials($identifier, $password);
    }

    /**
     * Exemple 9 : Connexion avec gestion d'erreur complète
     */
    public function loginWithErrorHandling(array $data): array
    {
        try {
            $loginDTO = LoginDTO::fromArray($data);

            // Vérifier si valide
            if (!$loginDTO->isValid()) {
                return [
                    'success' => false,
                    'message' => 'Veuillez renseigner tous les champs requis.',
                ];
            }

            // Vérifier le blocage
            if ($this->authService->isLocked($loginDTO->identifier)) {
                $lockoutTime = $this->authService->getLockoutTime($loginDTO->identifier);
                return [
                    'success' => false,
                    'message' => "Compte bloqué pour encore {$lockoutTime} secondes.",
                    'locked' => true,
                ];
            }

            // Tenter la connexion
            $result = $this->authService->login($loginDTO);

            return $result;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la connexion', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ];
        }
    }

    /**
     * Exemple 10 : Afficher un widget de status de tentatives
     */
    public function getLoginStatusWidget(string $identifier): string
    {
        $info = $this->authService->getLoginAttemptInfo($identifier);

        if ($info['isLocked']) {
            $minutes = ceil($info['lockoutTime'] / 60);
            return <<<HTML
                <div class="alert alert-warning">
                    <i class="bi bi-shield-lock-fill"></i>
                    Compte bloqué pour encore {$minutes} minute(s)
                </div>
            HTML;
        }

        if ($info['remainingAttempts'] < 3) {
            $remaining = $info['remainingAttempts'];
            return <<<HTML
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    Il vous reste {$remaining} tentative(s)
                </div>
            HTML;
        }

        return '';
    }

    /**
     * Exemple 11 : API Endpoint pour vérifier le statut
     */
    public function apiCheckLoginStatus(string $identifier): \Illuminate\Http\JsonResponse
    {
        $info = $this->authService->getLoginAttemptInfo($identifier);

        return response()->json([
            'locked' => $info['isLocked'],
            'remaining_attempts' => $info['remainingAttempts'],
            'lockout_time' => $info['lockoutTime'],
            'max_attempts' => $info['maxAttempts'],
        ]);
    }

    /**
     * Exemple 12 : Middleware personnalisé pour vérifier le blocage
     */
    public function checkLockedBeforeLogin($request, $next)
    {
        $identifier = $request->input('identifier');

        if ($identifier && $this->authService->isLocked($identifier)) {
            return response()->json([
                'error' => 'Compte temporairement bloqué',
                'lockout_time' => $this->authService->getLockoutTime($identifier),
            ], 429); // Too Many Requests
        }

        return $next($request);
    }

    /**
     * Exemple 13 : Dashboard admin - Liste des utilisateurs bloqués
     */
    public function getLockedUsers(array $identifiers): array
    {
        $lockedUsers = [];

        foreach ($identifiers as $identifier) {
            if ($this->authService->isLocked($identifier)) {
                $lockedUsers[] = [
                    'identifier' => $identifier,
                    'lockout_time' => $this->authService->getLockoutTime($identifier),
                    'remaining_time_minutes' => ceil($this->authService->getLockoutTime($identifier) / 60),
                ];
            }
        }

        return $lockedUsers;
    }

    /**
     * Exemple 14 : Notification après connexion réussie
     */
    public function loginWithNotification(array $data): array
    {
        $result = $this->authService->login(LoginDTO::fromArray($data));

        if ($result['success']) {
            // Envoyer une notification
            // notification()->success('Bienvenue ' . $result['user']->name);

            // Ou utiliser session flash
            session()->flash('success', 'Bienvenue ' . $result['user']->name);
            session()->flash('last_login', $result['user']->last_login_at?->diffForHumans());
        }

        return $result;
    }

    /**
     * Exemple 15 : Connexion avec redirection conditionnelle
     */
    public function loginAndRedirect(array $data): \Illuminate\Http\RedirectResponse
    {
        $result = $this->authService->login(LoginDTO::fromArray($data));

        if ($result['success']) {
            $user = $result['user'];

            // Redirection basée sur le rôle
            return match($user->role->name) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                default => redirect()->route('dashboard'),
            };
        }

        return back()->withErrors(['login' => $result['message']]);
    }
}

/**
 * UTILISATION DANS UN CONTROLLER
 */

// use App\Services\AuthenticationService;
// use App\DTOs\Auth\LoginDTO;

// public function login(Request $request, AuthenticationService $authService)
// {
//     $loginDTO = LoginDTO::fromArray([
//         'identifier' => $request->input('identifier'),
//         'password' => $request->input('password'),
//         'remember' => $request->boolean('remember'),
//         'ip_address' => $request->ip(),
//         'user_agent' => $request->userAgent(),
//     ]);
//
//     $result = $authService->login($loginDTO);
//
//     if ($result['success']) {
//         return redirect()->intended('dashboard');
//     }
//
//     return back()->withErrors(['login' => $result['message']]);
// }

/**
 * UTILISATION DANS UNE API
 */

// public function apiLogin(Request $request, AuthenticationService $authService)
// {
//     $loginDTO = LoginDTO::fromArray($request->all());
//     $result = $authService->login($loginDTO);
//
//     if ($result['success']) {
//         $token = $result['user']->createToken('auth-token')->plainTextToken;
//
//         return response()->json([
//             'success' => true,
//             'token' => $token,
//             'user' => $result['user'],
//         ]);
//     }
//
//     return response()->json([
//         'success' => false,
//         'message' => $result['message'],
//         'remaining_attempts' => $result['remainingAttempts'],
//     ], 401);
// }
