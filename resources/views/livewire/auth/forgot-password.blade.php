<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo / Titre -->
                        <div class="text-center mb-4">
                            <h1 class="h3 mb-3 fw-bold">Mot de passe oublié</h1>
                            <p class="text-muted">
                                Vous avez oublié votre mot de passe? Pas de problème.
                                Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation.
                            </p>
                        </div>

                        @if ($status)
                            <div class="alert alert-success" role="alert">
                                {{ $status }}
                            </div>
                        @endif

                        <!-- Formulaire -->
                        <form wire:submit="sendResetLink">
                            <!-- Email -->
                            <div class="mb-4">
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

                            <!-- Bouton d'envoi -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        Envoyer le lien de réinitialisation
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Envoi en cours...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Lien de retour -->
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none" wire:navigate>
                                <i class="bi bi-arrow-left me-1"></i>
                                Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
