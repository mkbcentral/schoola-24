<div>
    <x-navigation.bread-crumb icon='bi bi-currency-exchange' label="Gestion Taux de Change">
        <x-navigation.bread-crumb-item label='Taux de Change' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="row">
            <!-- Formulaire de mise à jour du taux -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Modifier le Taux de Change
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($message)
                            <div class="alert alert-{{ $messageType === 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                                role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <h6 class="text-muted mb-1">Taux Actuel</h6>
                                    <h3 class="mb-0 text-primary">
                                        1 USD = {{ app_format_number($currentRate, 0) }} CDF
                                    </h3>
                                </div>
                                <div>
                                    <button wire:click="refreshRate" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-arrow-clockwise"></i> Rafraîchir
                                    </button>
                                </div>
                            </div>
                        </div>

                        <form wire:submit.prevent="updateRate">
                            <div class="mb-3">
                                <label for="newRate" class="form-label fw-bold">
                                    <i class="bi bi-currency-dollar me-1"></i>
                                    Nouveau Taux (1 USD = ? CDF)
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="bi bi-cash"></i>
                                    </span>
                                    <input type="number" step="0.01"
                                        class="form-control @error('newRate') is-invalid @enderror" id="newRate"
                                        wire:model="newRate" placeholder="Ex: 2850">
                                    <span class="input-group-text">CDF</span>
                                </div>
                                @error('newRate')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Aperçu :</strong>
                                {{ $exampleUSD }} USD = {{ app_format_number($exampleCDF, 0) }} CDF
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-circle me-2"></i>
                                Mettre à jour le taux
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historique des taux -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Historique des Taux
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($history->isEmpty())
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucun historique disponible</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th class="text-end">Taux (CDF)</th>
                                            <th class="text-center">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history as $rate)
                                            <tr>
                                                <td>
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $rate->created_at->format('d/m/Y H:i') }}
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $rate->created_at->diffForHumans() }}
                                                    </small>
                                                </td>
                                                <td class="text-end">
                                                    <strong>{{ app_format_number($rate->amount, 2) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    @if (!$rate->is_changed)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Actif
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-archive"></i> Archivé
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-calculator me-2"></i>
                            Exemples de Conversion
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small">USD → CDF</div>
                                    <div class="h5 mb-0">100 USD</div>
                                    <div class="text-primary fw-bold">
                                        {{ app_format_number($currentRate * 100, 0) }} CDF
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small">CDF → USD</div>
                                    <div class="h5 mb-0">{{ app_format_number($currentRate * 100, 0) }} CDF</div>
                                    <div class="text-primary fw-bold">100 USD</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small">USD → CDF</div>
                                    <div class="h5 mb-0">500 USD</div>
                                    <div class="text-primary fw-bold">
                                        {{ app_format_number($currentRate * 500, 0) }} CDF
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <div class="text-muted small">USD → CDF</div>
                                    <div class="h5 mb-0">1000 USD</div>
                                    <div class="text-primary fw-bold">
                                        {{ app_format_number($currentRate * 1000, 0) }} CDF
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
