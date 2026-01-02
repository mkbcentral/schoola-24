<div>
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-grid-3x3-gap text-primary"></i>
                    Mes Modules
                </h3>
                <p class="text-muted mb-0">Les modules auxquels votre école a accès</p>
            </div>
        </div>

        @if ($modules->count() > 0)
            {{-- Statistiques --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; border: none;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-2 text-white-50 fw-semibold">Modules Actifs</h6>
                                    <h2 class="mb-0 fw-bold">{{ $modules->count() }}</h2>
                                    <small class="text-white-50">Module(s) disponible(s)</small>
                                </div>
                                <i class="bi bi-puzzle" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; border: none;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-2 text-white-50 fw-semibold">Coût Total</h6>
                                    <h2 class="mb-0 fw-bold">{{ number_format($totalCost, 0, ',', ' ') }} FC</h2>
                                    <small class="text-white-50">Valeur de vos modules</small>
                                </div>
                                <i class="bi bi-cash-stack" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Liste des modules --}}
            <div class="row g-4">
                @foreach ($modules as $module)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: transform 0.3s;"
                             onmouseover="this.style.transform='translateY(-5px)'"
                             onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="me-3">
                                        <div class="p-3 bg-light rounded-3">
                                            <i class="{{ $module->icon }} text-primary" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-2 fw-bold">{{ $module->name }}</h5>
                                        <p class="text-muted small mb-2">{{ $module->description }}</p>
                                        <span class="badge bg-success">
                                            <i class="bi bi-cash me-1"></i>{{ $module->formatted_price }}
                                        </span>
                                    </div>
                                </div>

                                @if ($module->features->count() > 0)
                                    <hr class="my-3">
                                    <h6 class="mb-3 fw-bold">
                                        <i class="bi bi-list-stars me-2"></i>Fonctionnalités disponibles :
                                    </h6>
                                    <div class="d-grid gap-2">
                                        @foreach ($module->features as $feature)
                                            <a href="{{ route($feature->url) }}"
                                               class="btn btn-outline-primary btn-sm text-start d-flex align-items-center justify-content-between"
                                               style="border-radius: 8px;">
                                                <span>
                                                    <i class="{{ $feature->icon }} me-2"></i>
                                                    {{ $feature->name }}
                                                </span>
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>Aucune fonctionnalité configurée
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Aucun module --}}
            <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-puzzle" style="font-size: 6rem; color: #e0e0e0;"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Aucun module actif</h4>
                    <p class="text-muted mb-4">
                        Votre école n'a actuellement accès à aucun module.
                        <br>
                        Contactez l'administrateur pour obtenir l'accès aux modules.
                    </p>
                    <div class="alert alert-info d-inline-block" style="border-radius: 8px; border: none;">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <small>Pour plus d'informations, contactez le support</small>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
