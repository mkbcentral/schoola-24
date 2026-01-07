<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo / Titre -->
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-3 fw-bold">Schoola</h1>
                            <p class="text-muted">Créer un nouveau compte</p>
                        </div>

                        <!-- Formulaire d'inscription -->
                        <form wire:submit="register">
                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    wire:model="name"
                                    placeholder="Jean Dupont"
                                    autofocus
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    wire:model="email"
                                    placeholder="exemple@email.com"
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
                                <div class="form-text">
                                    Minimum 8 caractères
                                </div>
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    wire:model="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                >
                            </div>

                            <!-- Conditions d'utilisation -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input @error('terms') is-invalid @enderror"
                                        type="checkbox"
                                        id="terms"
                                        wire:model="terms"
                                        required
                                    >
                                    <label class="form-check-label" for="terms">
                                        J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a>
                                        et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bouton d'inscription -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        S'inscrire
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Inscription...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Lien de connexion -->
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Vous avez déjà un compte?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" wire:navigate>
                                    Se connecter
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
