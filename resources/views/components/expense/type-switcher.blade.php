@props(['expenseType' => 'fee'])

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-{{ $expenseType === 'fee' ? 'primary' : 'outline-primary' }}"
                    wire:click="switchExpenseType('fee')" wire:loading.attr="disabled" wire:target="switchExpenseType">
                    <i class="bi bi-receipt me-2" wire:loading.remove wire:target="switchExpenseType('fee')"></i>
                    <span class="spinner-border spinner-border-sm me-2" wire:loading
                        wire:target="switchExpenseType('fee')"></span>
                    Dépenses sur Frais
                </button>
                <button type="button" class="btn btn-{{ $expenseType === 'other' ? 'primary' : 'outline-primary' }}"
                    wire:click="switchExpenseType('other')" wire:loading.attr="disabled"
                    wire:target="switchExpenseType">
                    <i class="bi bi-box-seam me-2" wire:loading.remove wire:target="switchExpenseType('other')"></i>
                    <span class="spinner-border spinner-border-sm me-2" wire:loading
                        wire:target="switchExpenseType('other')"></span>
                    Autres Dépenses
                </button>
            </div>

            <button class="btn btn-success" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#expenseFormOffcanvas" aria-controls="expenseFormOffcanvas"
                wire:click="openCreateModal">
                <i class="bi bi-plus-circle me-2"></i>
                Nouvelle Dépense
            </button>
        </div>
    </div>
</div>
