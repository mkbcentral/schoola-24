<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo / Titre -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-shield-check text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <h1 class="h3 mb-3 fw-bold">Authentification à deux facteurs</h1>
                            <p class="text-muted">
                                @if ($useRecoveryCode)
                                    Veuillez entrer l'un de vos codes de récupération d'urgence.
                                @else
                                    Veuillez entrer le code d'authentification fourni par votre application.
                                @endif
                            </p>
                        </div>

                        <!-- Formulaire -->
                        <form wire:submit="authenticate">
                            @if ($useRecoveryCode)
                                <!-- Code de récupération -->
                                <div class="mb-4">
                                    <label for="recovery_code" class="form-label">Code de récupération</label>
                                    <input
                                        type="text"
                                        class="form-control @error('recovery_code') is-invalid @enderror"
                                        id="recovery_code"
                                        wire:model="recovery_code"
                                        placeholder="abcd-1234-efgh-5678"
                                        autofocus
                                        required
                                    >
                                    @error('recovery_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @else
                                <!-- Code d'authentification -->
                                <div class="mb-4">
                                    <label for="code" class="form-label">Code d'authentification</label>
                                    <input
                                        type="text"
                                        class="form-control @error('code') is-invalid @enderror"
                                        id="code"
                                        wire:model="code"
                                        placeholder="123456"
                                        autofocus
                                        required
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        autocomplete="one-time-code"
                                    >
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Basculer entre code et code de récupération -->
                            <div class="mb-4 text-center">
                                <button
                                    type="button"
                                    class="btn btn-link text-decoration-none p-0"
                                    wire:click="toggleRecoveryCode"
                                >
                                    @if ($useRecoveryCode)
                                        Utiliser un code d'authentification
                                    @else
                                        Utiliser un code de récupération
                                    @endif
                                </button>
                            </div>

                            <!-- Bouton de validation -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        Se connecter
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Vérification...
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
