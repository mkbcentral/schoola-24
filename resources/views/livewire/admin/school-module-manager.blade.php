<div>
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-building text-primary"></i>
                    Gestion des Modules
                </h3>
                <p class="text-muted mb-0">
                    École : <strong class="text-dark">{{ $school->name }}</strong>
                </p>
            </div>
            <a wire:navigate href="{{ route('v2.schools.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Retour aux écoles
            </a>
        </div>

        {{-- Messages Flash --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                 style="border-radius: 12px; border: none;">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                 style="border-radius: 12px; border: none;">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Liste des modules disponibles --}}
            <div class="col-md-8">
                <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-header bg-light border-0 p-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-list-check me-2"></i>Modules Disponibles
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse ($availableModules as $module)
                            <div class="card mb-3 {{ in_array($module->id, $selectedModuleIds) ? 'border-primary' : '' }}"
                                 style="border-radius: 10px; border-width: 2px;">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="form-check form-check-lg">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       id="module_{{ $module->id }}"
                                                       wire:model.live="selectedModuleIds"
                                                       value="{{ $module->id }}"
                                                       style="width: 1.5rem; height: 1.5rem;">
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <i class="{{ $module->icon }} text-primary" style="font-size: 2.5rem;"></i>
                                        </div>

                                        <div class="col">
                                            <h6 class="mb-1 fw-bold">{{ $module->name }}</h6>
                                            <p class="text-muted small mb-2">{{ $module->description }}</p>
                                            <span class="badge bg-info text-dark">
                                                <i class="bi bi-list-stars me-1"></i>
                                                {{ $module->features->count() }} fonctionnalité(s)
                                            </span>
                                        </div>

                                        <div class="col-auto text-end">
                                            <h5 class="text-success mb-2 fw-bold">{{ $module->formatted_price }}</h5>
                                            @if ($module->features->count() > 0)
                                                <button class="btn btn-sm btn-outline-primary"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#features_{{ $module->id }}">
                                                    <i class="bi bi-eye me-1"></i> Voir détails
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Liste des fonctionnalités --}}
                                    @if ($module->features->count() > 0)
                                        <div class="collapse mt-3" id="features_{{ $module->id }}">
                                            <hr>
                                            <h6 class="fw-bold mb-3">
                                                <i class="bi bi-list-ul me-2"></i>Fonctionnalités :
                                            </h6>
                                            <div class="row g-2">
                                                @foreach ($module->features as $feature)
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                                            <i class="{{ $feature->icon }} text-muted me-2"></i>
                                                            <div>
                                                                <div class="fw-semibold small">{{ $feature->name }}</div>
                                                                <small class="text-muted">{{ $feature->url }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <p class="mb-0">Aucun module disponible</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Résumé et actions --}}
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px; border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="card-header text-white p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-cart-check me-2"></i>Résumé
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-check2-square me-2"></i>Modules sélectionnés :
                        </h6>

                        @if (count($selectedModuleIds) > 0)
                            <div class="mb-3">
                                @foreach ($availableModules->whereIn('id', $selectedModuleIds) as $module)
                                    <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $module->icon }} text-primary me-2"></i>
                                            <div>
                                                <div class="fw-semibold small">{{ $module->name }}</div>
                                                <small class="text-muted">{{ $module->formatted_price }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-muted bg-light rounded">
                                <i class="bi bi-inbox d-block mb-2 fs-4"></i>
                                <small>Aucun module sélectionné</small>
                            </div>
                        @endif

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
                            <strong class="text-dark">Total :</strong>
                            <h4 class="text-success mb-0 fw-bold">{{ number_format($totalCost, 0, ',', ' ') }} FC</h4>
                        </div>

                        <button wire:click="save"
                                class="btn btn-primary btn-lg w-100 mb-3"
                                {{ count($selectedModuleIds) == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-check-lg me-2"></i> Enregistrer les modifications
                        </button>

                        <div class="alert alert-info mb-0" style="border-radius: 8px; border: none;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                <small>Sélectionnez les modules que vous souhaitez affecter à cette école.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
