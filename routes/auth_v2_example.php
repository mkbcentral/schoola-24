<?php

/**
 * Routes d'authentification V2
 *
 * À ajouter dans routes/web.php AVANT les routes protégées
 */

use App\Livewire\Application\V2\Auth\Login;
use App\Services\AuthenticationService;

// ==================== ROUTES D'AUTHENTIFICATION ====================

// Routes pour les invités (non connectés)
Route::middleware('guest')->group(function () {
    // Page de connexion
    Route::get('/v2/login', Login::class)->name('v2.login');

    // Ou si vous voulez remplacer la route login par défaut :
    // Route::get('/login', Login::class)->name('login');

    // Page de réinitialisation de mot de passe (à implémenter)
    // Route::get('/password/reset', PasswordReset::class)->name('password.request');
});

// Routes pour les utilisateurs authentifiés
Route::middleware(['auth', 'verified'])->group(function () {

    // Déconnexion
    Route::post('/logout', function (AuthenticationService $authService) {
        $authService->logout();
        return redirect()->route('v2.login')->with('success', 'Vous avez été déconnecté avec succès.');
    })->name('logout');

    // Alternative avec redirection vers login par défaut
    // Route::post('/logout', function (AuthenticationService $authService) {
    //     $authService->logout();
    //     return redirect()->route('login');
    // })->name('logout');

    // Dashboard (exemple)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ... Vos autres routes protégées ici
});

// ==================== ROUTES ADMIN (Optionnel) ====================

// Routes pour les administrateurs uniquement
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {

    // Réinitialiser les tentatives de connexion pour un utilisateur
    Route::post('/auth/reset-attempts/{identifier}', function (string $identifier, AuthenticationService $authService) {
        $authService->resetLoginAttempts($identifier);
        return back()->with('success', 'Les tentatives de connexion ont été réinitialisées.');
    })->name('admin.auth.reset-attempts');

    // Vérifier le statut de blocage d'un utilisateur
    Route::get('/auth/status/{identifier}', function (string $identifier, AuthenticationService $authService) {
        $info = $authService->getLoginAttemptInfo($identifier);
        return response()->json($info);
    })->name('admin.auth.status');
});
