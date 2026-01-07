<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo / Titre -->
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-3 fw-bold">Réinitialiser le mot de passe</h1>
                            <p class="text-muted">Veuillez entrer votre nouveau mot de passe</p>
                        </div>

                        <!-- Formulaire -->
                        <form wire:submit="resetPassword">
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

                            <!-- Nouveau mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
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

                            <!-- Confirmation du mot de passe -->
                            <div class="mb-4">
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

                            <!-- Bouton de réinitialisation -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        Réinitialiser le mot de passe
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Réinitialisation...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
