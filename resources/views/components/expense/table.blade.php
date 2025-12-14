@props([
    'expenses' => [],
    'expenseType' => 'fee',
])

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Liste des {{ $expenseType === 'fee' ? 'Dépenses sur Frais' : 'Autres Dépenses' }}
        </h5>
    </div>
    <div class="card-body">
        @if ($expenses->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Aucune dépense trouvée</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Mois</th>
                            <th>Catégorie</th>
                            @if ($expenseType === 'fee')
                                <th>Type Frais</th>
                            @else
                                <th>Source</th>
                            @endif
                            <th class="text-end">USD ($)</th>
                            <th class="text-end">CDF (FC)</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>
                                    <small class="text-muted">
                                        {{ $expense->created_at->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>{{ $expense->description }}</td>
                                <td>
                                    <span class="badge text-bg-secondary">
                                        {{ format_fr_month_name($expense->month) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-bg-info">
                                        {{ $expense->categoryExpense->name ?? 'N/A' }}
                                    </span>
                                </td>
                                @if ($expenseType === 'fee')
                                    <td>
                                        <span class="badge text-bg-primary">
                                            {{ $expense->categoryFee->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $expense->otherSourceExpense->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                @endif
                                <td class="text-end">
                                    @if ($expense->currency === 'USD')
                                        <strong class="text-success">
                                            {{ app_format_number($expense->amount, 1) }}
                                        </strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($expense->currency === 'CDF')
                                        <strong class="text-primary">
                                            {{ app_format_number($expense->amount, 0) }}
                                        </strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2"
                                        wire:key="validation-{{ $expense->id }}">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input expense-toggle-switch" type="checkbox"
                                                role="switch" id="validation-{{ $expense->id }}"
                                                wire:click="toggleValidation({{ $expense->id }})"
                                                {{ $expense->is_validated ? 'checked' : '' }}
                                                wire:loading.attr="disabled"
                                                wire:target="toggleValidation({{ $expense->id }})">
                                        </div>
                                        <div wire:loading.remove wire:target="toggleValidation({{ $expense->id }})">
                                            @if ($expense->is_validated)
                                                <span class="badge text-bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Validée
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>En attente
                                                </span>
                                            @endif
                                        </div>
                                        <div wire:loading wire:target="toggleValidation({{ $expense->id }})">
                                            <span class="spinner-border spinner-border-sm text-primary"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if (!$expense->is_validated)
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" type="button"
                                                data-bs-toggle="offcanvas" data-bs-target="#expenseFormOffcanvas"
                                                aria-controls="expenseFormOffcanvas"
                                                wire:click="openEditModal({{ $expense->id }})" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger"
                                                wire:click="confirmDelete({{ $expense->id }})" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted border">
                                            <i class="bi bi-lock me-1"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3" wire:loading.remove>
                {{ $expenses->links() }}
            </div>

            <!-- Indicateur de chargement pour la pagination -->
            <div class="text-center py-3" wire:loading>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        @endif
    </div>
</div>
