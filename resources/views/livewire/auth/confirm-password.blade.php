<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo / Titre -->
                        <div class="text-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-shield-lock text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                            <h1 class="h3 mb-3 fw-bold">Zone sécurisée</h1>
                            <p class="text-muted">
                                Cette action est sensible. Veuillez confirmer votre mot de passe pour continuer.
                            </p>
                        </div>

                        <!-- Formulaire -->
                        <form wire:submit="confirmPassword">
                            <!-- Mot de passe -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    wire:model="password"
                                    placeholder="••••••••"
                                    autofocus
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Bouton de confirmation -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        Confirmer
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Vérification...
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
