<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Icône -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-envelope-check text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h1 class="h3 mb-3 fw-bold">Vérifiez votre email</h1>
                            <p class="text-muted">
                                Merci de vous être inscrit! Avant de commencer, pourriez-vous vérifier votre adresse email
                                en cliquant sur le lien que nous venons de vous envoyer? Si vous n'avez pas reçu l'email,
                                nous vous en enverrons un autre avec plaisir.
                            </p>
                        </div>

                        @if ($status)
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ $status }}
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <button
                                type="button"
                                class="btn btn-primary"
                                wire:click="resendVerificationEmail"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>
                                    Renvoyer l'email de vérification
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Envoi en cours...
                                </span>
                            </button>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link text-decoration-none">
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
