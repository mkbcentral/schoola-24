<div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient-primary py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <!-- Section Formulaire (Gauche) -->
                        <div class="col-md-6 col-lg-5 d-flex flex-column">
                            <div class="card-body p-4 p-lg-5 d-flex flex-column justify-content-center">
                                <!-- Logo et Titre -->
                                <x-auth.v2.form-header />

                                <!-- Messages d'erreur globaux -->
                                @error('login')
                                    <x-auth.v2.alert 
                                        type="danger"
                                        icon="exclamation-triangle-fill"
                                        :message="$message"
                                    />
                                @enderror

                                <!-- Alerte de blocage -->
                                @if($isLocked && $lockoutTime)
                                    <x-auth.v2.alert 
                                        type="warning"
                                        icon="shield-lock-fill"
                                        title="Compte temporairement bloqué"
                                        :message="'Veuillez patienter ' . ceil($lockoutTime / 60) . ' minute(s)'"
                                        :dismissible="false"
                                    />
                                @endif

                                <!-- Formulaire -->
                                <form wire:submit.prevent="login" class="needs-validation" novalidate>
                                    <!-- Identifiant -->
                                    <x-auth.v2.input-field 
                                        label="Identifiant"
                                        icon="person"
                                        name="identifier"
                                        model="identifier"
                                        placeholder="Nom d'utilisateur ou email"
                                        autocomplete="username"
                                        :autofocus="true"
                                        :disabled="$isLocked"
                                        delay="0.1s"
                                    />

                                    <!-- Mot de passe -->
                                    <x-auth.v2.input-field 
                                        label="Mot de passe"
                                        icon="lock"
                                        :type="$showPassword ? 'text' : 'password'"
                                        name="password"
                                        model="password"
                                        placeholder="Votre mot de passe"
                                        autocomplete="current-password"
                                        :disabled="$isLocked"
                                        delay="0.2s"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-toggle-password @error('password') error-state @enderror"
                                            wire:click="togglePasswordVisibility"
                                            @if($isLocked) disabled @endif
                                        >
                                            <i class="bi bi-eye{{ $showPassword ? '-slash' : '' }}"></i>
                                        </button>
                                    </x-auth.v2.input-field>

                                    <!-- Se souvenir de moi & Mot de passe oublié -->
                                    <div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up" style="animation-delay: 0.3s;">
                                        <div class="form-check custom-checkbox">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="remember"
                                                wire:model="remember"
                                                @if($isLocked) disabled @endif
                                            >
                                            <label class="form-check-label text-muted" for="remember">
                                                Se souvenir de moi
                                            </label>
                                        </div>
                                        <button
                                            type="button"
                                            class="btn btn-link text-decoration-none p-0 forgot-password-link"
                                            wire:click="goToForgotPassword"
                                        >
                                            <small>Mot de passe oublié ?</small>
                                        </button>
                                    </div>

                                    <!-- Tentatives restantes -->
                                    @if($remainingAttempts !== null && $remainingAttempts > 0 && $remainingAttempts < 3)
                                        <x-auth.v2.alert 
                                            type="info"
                                            icon="info-circle-fill"
                                            :message="'Il vous reste ' . $remainingAttempts . ' tentative(s)'"
                                            :dismissible="false"
                                        />
                                    @endif

                                    <!-- Bouton de connexion -->
                                    <div class="d-grid mb-3 animate-slide-up" style="animation-delay: 0.4s;">
                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-lg btn-login position-relative"
                                            wire:loading.attr="disabled"
                                            @if($isLocked) disabled @endif
                                        >
                                            <span wire:loading.remove wire:target="login">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                                Se connecter
                                            </span>
                                            <span wire:loading wire:target="login">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                Connexion en cours...
                                            </span>
                                        </button>
                                    </div>
                                </form>

                                <!-- Footer -->
                                <div class="text-center mt-4 animate-fade-in" style="animation-delay: 0.5s;">
                                    <small class="text-muted security-badge">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Connexion sécurisée SSL
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Section Image/Description (Droite) -->
                        <x-auth.v2.info-section />
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        © {{ date('Y') }} Schoola. Tous droits réservés.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


