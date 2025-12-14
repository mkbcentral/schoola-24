<div class="container-fluid">
    {{-- Filtres --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">🔍 Filtres de recherche</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                {{-- Date --}}
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" wire:model.live="date">
                </div>

                {{-- Mois --}}
                <div class="col-md-3">
                    <label class="form-label">Mois</label>
                    <select class="form-select" wire:model.live="month">
                        <option value="">Tous les mois</option>
                        <option value="JANVIER">Janvier</option>
                        <option value="FEVRIER">Février</option>
                        <option value="MARS">Mars</option>
                        <option value="AVRIL">Avril</option>
                        <option value="MAI">Mai</option>
                        <option value="JUIN">Juin</option>
                        <option value="JUILLET">Juillet</option>
                        <option value="AOUT">Août</option>
                        <option value="SEPTEMBRE">Septembre</option>
                        <option value="OCTOBRE">Octobre</option>
                        <option value="NOVEMBRE">Novembre</option>
                        <option value="DECEMBRE">Décembre</option>
                    </select>
                </div>

                {{-- Devise --}}
                <div class="col-md-2">
                    <label class="form-label">Devise</label>
                    <select class="form-select" wire:model.live="currency">
                        <option value="">Toutes</option>
                        <option value="CDF">CDF</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                    </select>
                </div>

                {{-- Statut --}}
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select class="form-select" wire:model.live="isPaid">
                        <option value="">Tous</option>
                        <option value="1">Payés</option>
                        <option value="0">Non payés</option>
                    </select>
                </div>

                {{-- Recherche --}}
                <div class="col-md-2">
                    <label class="form-label">Recherche</label>
                    <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                        placeholder="Nom élève...">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button class="btn btn-secondary" wire:click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Paiements</h6>
                    <h3 class="mb-0">{{ number_format($totalCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Payés</h6>
                    <h3 class="mb-0">{{ number_format($statistics['paid_count'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">En attente</h6>
                    <h3 class="mb-0">{{ number_format($statistics['unpaid_count'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Taux de paiement</h6>
                    <h3 class="mb-0">{{ number_format($statistics['payment_rate'] ?? 0, 2) }}%</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Totaux par devise --}}
    @if (!empty($totalsByCurrency))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">💰 Totaux par devise</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($totalsByCurrency as $currency => $amount)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3 text-center">
                                <h6 class="text-muted mb-2">{{ $currency }}</h6>
                                <h4 class="mb-0 text-primary">
                                    {{ number_format($amount, 2) }}
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Liste des paiements --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">📋 Liste des paiements</h5>
            <select class="form-select w-auto" wire:model.live="perPage">
                <option value="10">10 par page</option>
                <option value="15">15 par page</option>
                <option value="25">25 par page</option>
                <option value="50">50 par page</option>
                <option value="100">100 par page</option>
            </select>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Paiement</th>
                            <th>Élève</th>
                            <th>Frais</th>
                            <th>Montant</th>
                            <th>Devise</th>
                            <th>Mois</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <code>{{ $payment->payment_number }}</code>
                                </td>
                                <td>
                                    <strong>{{ $payment->registration->student->name }}</strong>
                                </td>
                                <td>{{ $payment->scolarFee->categoryFee->name }}</td>
                                <td class="text-end">
                                    <strong>{{ number_format($payment->scolarFee->amount, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $payment->scolarFee->categoryFee->currency }}
                                    </span>
                                </td>
                                <td>{{ $payment->month ?? '-' }}</td>
                                <td>
                                    @if ($payment->is_paid)
                                        <span class="badge bg-success">Payé</span>
                                    @else
                                        <span class="badge bg-warning">En attente</span>
                                    @endif
                                </td>
                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info" title="Imprimer">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Aucun paiement trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
