<div class="min-vh-100 d-flex align-items-center justify-content-center py-3 py-lg-4">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row justify-content-center align-items-center">
            <!-- Colonne gauche : Formulaire -->
            <div class="col-12 col-lg-5 col-xl-4 mb-4 mb-lg-0">
                <div class="auth-card border-0">
                    <div class="card-body p-3 p-lg-4">
                        <!-- Logo / Titre -->
                        <div class="mb-3 text-center">
                            <div class="logo-icon mb-2">
                                <i class="bi bi-mortarboard-fill text-primary" style="font-size: 2.5rem;"></i>
                            </div>
                            <h1 class="h3 mb-1 font-weight-bold text-primary">
                                Schoola
                            </h1>
                            <p class="text-muted mb-0 small">Connectez-vous à votre espace</p>
                        </div>

                        <!-- Formulaire de connexion -->
                        <form wire:submit="login">
                            <!-- Message de statut -->
                            @if($statusMessage)
                                <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center" style="border-radius: 12px; border: 2px solid #3b82f6; background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);">
                                    <div class="spinner-border spinner-border-sm text-primary mr-2" role="status" style="width: 1rem; height: 1rem;">
                                        <span class="sr-only">Chargement...</span>
                                    </div>
                                    <span class="small font-weight-semibold text-primary mb-0">{{ $statusMessage }}</span>
                                </div>
                            @endif

                            <!-- Message d'avertissement tentatives -->
                            @if($warningMessage && !$statusMessage)
                                <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-center" style="border-radius: 12px; border: 2px solid #f59e0b; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                                    <i class="bi bi-exclamation-triangle-fill text-warning mr-2" style="font-size: 1.2rem;"></i>
                                    <span class="small font-weight-semibold text-warning mb-0">{{ $warningMessage }}</span>
                                </div>
                            @endif

                            <!-- Indicateur de tentatives restantes -->
                            @if($remainingAttempts !== null && $remainingAttempts < 5 && !$statusMessage)
                                <div class="mb-3 d-flex align-items-center justify-content-between p-3" style="border-radius: 12px; background: linear-gradient(135deg, {{ $remainingAttempts <= 2 ? '#fef2f2' : '#f0fdf4' }} 0%, {{ $remainingAttempts <= 2 ? '#fee2e2' : '#dcfce7' }} 100%); border: 2px solid {{ $remainingAttempts <= 2 ? '#ef4444' : '#22c55e' }};">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px; background: {{ $remainingAttempts <= 2 ? '#fee2e2' : '#dcfce7' }}; border: 2px solid {{ $remainingAttempts <= 2 ? '#ef4444' : '#22c55e' }};">
                                            <i class="bi {{ $remainingAttempts <= 2 ? 'bi-shield-exclamation' : 'bi-shield-check' }}" style="font-size: 1.2rem; color: {{ $remainingAttempts <= 2 ? '#ef4444' : '#22c55e' }};"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="mb-0 small font-weight-bold" style="color: {{ $remainingAttempts <= 2 ? '#991b1b' : '#166534' }};">
                                                Tentatives restantes
                                            </p>
                                            <p class="mb-0" style="font-size: 11px; color: {{ $remainingAttempts <= 2 ? '#dc2626' : '#16a34a' }};">
                                                {{ $remainingAttempts <= 2 ? 'Attention, dernières tentatives !' : 'Sécurité du compte' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center" style="min-width: 50px; height: 50px; border-radius: 12px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        <div class="text-center">
                                            <span class="font-weight-bold d-block" style="font-size: 1.5rem; line-height: 1; color: {{ $remainingAttempts <= 2 ? '#ef4444' : '#22c55e' }};">
                                                {{ $remainingAttempts }}
                                            </span>
                                            <span style="font-size: 9px; color: {{ $remainingAttempts <= 2 ? '#dc2626' : '#16a34a' }}; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                {{ $remainingAttempts > 1 ? 'essais' : 'essai' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Email -->
                            <div class="mb-3">
                                <x-form.label for="email" class="form-label font-weight-semibold small mb-1">
                                    Adresse email
                                </x-form.label>
                                <x-form.input
                                    type="email"
                                    id="email"
                                    wire:model.blur="email"
                                    placeholder="exemple@email.com"
                                    icon="bi bi-envelope"
                                    error="email"
                                    autofocus

                                />
                                @error('email')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <x-form.label for="password" class="form-label font-weight-semibold small mb-1">
                                    Mot de passe
                                </x-form.label>
                                <x-form.input
                                    type="password"
                                    id="password"
                                    wire:model.blur="password"
                                    placeholder="••••••••"
                                    icon="bi bi-lock"
                                    error="password"

                                />
                                @error('password')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle mr-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Se souvenir de moi et mot de passe oublié -->
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="remember"
                                        wire:model="remember"
                                    >
                                    <label class="form-check-label small" for="remember">
                                        Se souvenir
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="small text-decoration-none font-weight-semibold text-primary" wire:navigate>
                                    <i class="bi bi-key mr-1"></i>Mot de passe oublié?
                                </a>
                            </div>

                            <!-- Bouton de connexion -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-lg btn-block font-weight-semibold" wire:loading.attr="disabled" :disabled="$isLoading">
                                    <span wire:loading.remove wire:target="login">
                                        <i class="bi bi-box-arrow-in-right mr-2"></i>Se connecter
                                    </span>
                                    <span wire:loading wire:target="login">
                                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                                        <span>{{ $statusMessage ?: 'Connexion...' }}</span>
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="divider" style="margin: 20px 0;">
                            <span>ou</span>
                        </div>

                        <!-- Lien d'inscription -->
                        <div class="text-center">
                            <p class="text-muted mb-0 small">
                                Nouveau?
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none font-weight-semibold" wire:navigate>
                                    Créer un compte <i class="bi bi-arrow-right ml-1"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-3">
                    <p class="small text-white mb-0" style="opacity: 0.8;">
                        © {{ date('Y') }} Schoola
                    </p>
                </div>
            </div>

            <!-- Colonne droite : Description et fonctionnalités -->
            <div class="col-12 col-lg-6 col-xl-5 d-none d-lg-flex align-items-center pl-lg-4 ml-lg-3">
                <div class="info-section">
                    <!-- Titre principal -->
                    <div class="mb-4">
                        <h2 class="h3 font-weight-bold text-white mb-2">
                            Bienvenue sur Schoola
                        </h2>
                        <p class="text-white mb-0" style="opacity: 0.9; font-size: 15px;">
                            La plateforme de gestion scolaire complète
                        </p>
                    </div>

                    <!-- Liste des fonctionnalités -->
                    <div class="row">
                        <!-- Fonctionnalité 1 -->
                        <div class="col-12 mb-3">
                            <div class="feature-item d-flex">
                                <div class="shrink-0">
                                    <div class="feature-icon" style="background: rgba(79, 70, 229, 0.15); width: 50px; height: 50px;">
                                        <i class="bi bi-people-fill text-white" style="font-size: 1.3rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ml-3">
                                    <h6 class="font-weight-semibold mb-1 text-white">Gestion des élèves</h6>
                                    <p class="text-white mb-0 small" style="opacity: 0.85;">
                                        Inscriptions, présences et performances
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Fonctionnalité 2 -->
                        <div class="col-12 mb-3">
                            <div class="feature-item d-flex">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon" style="background: rgba(16, 185, 129, 0.15); width: 50px; height: 50px;">
                                        <i class="bi bi-cash-stack text-white" style="font-size: 1.3rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ml-3">
                                    <h6 class="font-weight-semibold mb-1 text-white">Gestion financière</h6>
                                    <p class="text-white mb-0 small" style="opacity: 0.85;">
                                        Paiements et suivi en temps réel
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Fonctionnalité 3 -->
                        <div class="col-12 mb-3">
                            <div class="feature-item d-flex">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon" style="background: rgba(59, 130, 246, 0.15); width: 50px; height: 50px;">
                                        <i class="bi bi-graph-up-arrow text-white" style="font-size: 1.3rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ml-3">
                                    <h6 class="font-weight-semibold mb-1 text-white">Rapports détaillés</h6>
                                    <p class="text-white mb-0 small" style="opacity: 0.85;">
                                        Statistiques et analyses personnalisées
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Fonctionnalité 4 -->
                        <div class="col-12 mb-3">
                            <div class="feature-item d-flex">
                                <div class="flex-shrink-0">
                                    <div class="feature-icon" style="background: rgba(245, 158, 11, 0.15); width: 50px; height: 50px;">
                                        <i class="bi bi-chat-dots-fill text-white" style="font-size: 1.3rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ml-3">
                                    <h6 class="font-weight-semibold mb-1 text-white">Communication SMS</h6>
                                    <p class="text-white mb-0 small" style="opacity: 0.85;">
                                        Messagerie intégrée avec parents
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Citation ou badge de confiance -->
                    <div class="mt-4 p-3 trust-badge">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check text-primary mr-2" style="font-size: 2.5rem;"></i>
                            <div>
                                <p class="mb-0 font-weight-semibold text-primary small">Sécurisé et fiable</p>
                                <p class="mb-0 text-primary" style="opacity: 0.8; font-size: 12px;">
                                    Chiffrement de niveau bancaire
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
