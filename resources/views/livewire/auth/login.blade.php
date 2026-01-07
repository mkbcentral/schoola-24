<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo / Titre -->
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-3 fw-bold">Schoola</h1>
                            <p class="text-muted">Connectez-vous à votre compte</p>
                        </div>

                        <!-- Formulaire de connexion -->
                        <form wire:submit="login">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    wire:model="email"
                                    placeholder="exemple@email.com"
                                    autofocus
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    wire:model="password"
                                    placeholder="••••••••"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Se souvenir de moi et mot de passe oublié -->
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="remember"
                                        wire:model="remember"
                                    >
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="small text-decoration-none" wire:navigate>
                                    Mot de passe oublié?
                                </a>
                            </div>

                            <!-- Bouton de connexion -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        Se connecter
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Connexion...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Lien d'inscription -->
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Pas encore de compte?
                                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" wire:navigate>
                                    S'inscrire
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="text-center mt-4">
                    <p class="small text-muted">
                        © {{ date('Y') }} Schoola. Tous droits réservés.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
