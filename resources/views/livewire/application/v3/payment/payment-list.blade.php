<div class="card shadow-sm border-0">
    <div class="card-header border-0 bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2 text-primary"></i>
                Liste des paiements
            </h5>
            
            <!-- Filtres de statut -->
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" id="filter-all" wire:model.live="filterStatus" value="all" autocomplete="off">
                <label class="btn btn-outline-primary btn-sm" for="filter-all">
                    <i class="bi bi-list me-1"></i>Tous
                </label>

                <input type="radio" class="btn-check" id="filter-paid" wire:model.live="filterStatus" value="paid" autocomplete="off">
                <label class="btn btn-outline-success btn-sm" for="filter-paid">
                    <i class="bi bi-check-circle me-1"></i>Payés
                </label>

                <input type="radio" class="btn-check" id="filter-unpaid" wire:model.live="filterStatus" value="unpaid" autocomplete="off">
                <label class="btn btn-outline-warning btn-sm" for="filter-unpaid">
                    <i class="bi bi-clock-history me-1"></i>Non payés
                </label>
            </div>
        </div>

        <!-- Filtres supplémentaires -->
        <div class="row g-3 mt-2">
            <!-- Filtre par catégorie -->
            <div class="col-md-6">
                <label for="filter-category" class="form-label fw-semibold small">
                    <i class="bi bi-tag me-1"></i>Catégorie de frais
                </label>
                <select 
                    id="filter-category"
                    wire:model.live="filterCategoryFeeId"
                    class="form-select form-select-sm">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach($this->categoryFees as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre par date -->
            <div class="col-md-6">
                <label for="filter-date" class="form-label fw-semibold small">
                    <i class="bi bi-calendar-event me-1"></i>Date
                </label>
                <input 
                    type="date" 
                    id="filter-date"
                    wire:model.live="filterDate"
                    class="form-control form-control-sm">
            </div>
        </div>

        <!-- Total des paiements -->
        @if (!empty($totalsByCurrency))
            <div class="mt-3 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="fw-semibold text-secondary">
                        <i class="bi bi-wallet2 me-2 text-success"></i>
                        Total des paiements validés:
                    </span>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($totalsByCurrency as $currency => $total)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-cash-stack me-1"></i>
                                {{ number_format($total, 0, ',', ' ') }} {{ $currency }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="card-body p-0">
        <div wire:loading wire:target="filterStatus, filterCategoryFeeId, filterDate, loadPayments" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement des paiements...</p>
        </div>

        <div wire:loading.remove wire:target="filterStatus, filterCategoryFeeId, filterDate, loadPayments">
            @if(count($payments) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Élève</th>
                                <th>Catégorie</th>
                                <th>Mois</th>
                                <th class="text-center">Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr class="{{ $payment->is_paid ? 'table-success' : '' }}">
                                    <td>
                                        <div class="fw-semibold">{{ $payment->registration->student->name ?? 'N/A' }}</div>
                                        <small class="text-muted">
                                            {{ $payment->registration->code ?? '' }} - 
                                            {{ $payment->registration->classRoom->getOriginalClassRoomName() ?? '' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div>{{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}</div>
                                        <small class="text-muted">
                                            {{ number_format($payment->scolarFee->amount ?? 0, 0, ',', ' ') }} 
                                            {{ $payment->rate->currency_code ?? 'CDF' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->month }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($payment->is_paid)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Payé
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock-history me-1"></i>En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if(!$payment->is_paid)
                                                <!-- Valider -->
                                                <button 
                                                    type="button"
                                                    wire:click="validatePayment({{ $payment->id }})"
                                                    class="btn btn-outline-success"
                                                    title="Valider le paiement"
                                                    wire:loading.attr="disabled">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                
                                                <!-- Modifier -->
                                                <button 
                                                    type="button"
                                                    wire:click="editPayment({{ $payment->id }})"
                                                    class="btn btn-outline-primary"
                                                    title="Modifier"
                                                    wire:loading.attr="disabled">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                
                                                <!-- Supprimer -->
                                                <button 
                                                    type="button"
                                                    onclick="confirmDelete({{ $payment->id }})"
                                                    class="btn btn-outline-danger"
                                                    title="Supprimer"
                                                    wire:loading.attr="disabled">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-lock-fill me-1"></i>Validé
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                    <p class="mt-3 text-muted">
                        @if($selectedRegistrationId)
                            Aucun paiement trouvé pour cet élève
                        @else
                            Aucun paiement enregistré
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if(count($payments) > 0)
        <div class="card-footer bg-light border-0 text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Affichage de {{ count($payments) }} paiement(s)
        </div>
    @endif
</div>

<script>
    function confirmDelete(paymentId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')) {
            @this.call('deletePayment', paymentId);
        }
    }
</script>
