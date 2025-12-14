<div>
    {{-- Offcanvas Bootstrap pour formulaire de dépense --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="expenseFormOffcanvas"
        aria-labelledby="expenseFormOffcanvasLabel" data-bs-backdrop="true" data-bs-scroll="false" data-bs-keyboard="true"
        wire:ignore.self style="width: 600px">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="expenseFormOffcanvasLabel">
                <span wire:loading.remove wire:target="openModal,openEditModal">
                    <i class="bi bi-{{ $isEditing ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $isEditing ? 'Modifier' : 'Nouvelle' }}
                    {{ $expenseType === 'fee' ? 'Dépense sur Frais' : 'Autre Dépense' }}
                </span>
                <span wire:loading wire:target="openModal,openEditModal">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Chargement...
                </span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <div wire:loading.class="opacity-50" wire:target="openEditModal">
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-pencil me-1"></i>
                                Description
                            </label>
                            <textarea class="form-control @error('form.description') is-invalid @enderror" wire:model.blur="form.description"
                                rows="3" placeholder="Entrez une description détaillée..."></textarea>
                            @error('form.description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mois -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar me-1"></i>
                                Mois
                            </label>
                            <x-widget.list-month-fr wire:model.blur='form.month' :error="'form.month'" />
                            @error('form.month')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Montant -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-cash me-1"></i>
                                Montant
                            </label>
                            <input type="number" step="0.01"
                                class="form-control @error('form.amount') is-invalid @enderror"
                                wire:model.blur="form.amount" placeholder="0.00">
                            @error('form.amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Devise -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-currency-dollar me-1"></i>
                                Devise
                            </label>
                            <select class="form-select @error('form.currency') is-invalid @enderror"
                                wire:model.blur="form.currency">
                                <option value="USD">USD ($)</option>
                                <option value="CDF">CDF (FC)</option>
                            </select>
                            @error('form.currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catégorie de Dépense -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-tag me-1"></i>
                                Catégorie de Dépense
                            </label>
                            <select class="form-select @error('form.categoryExpenseId') is-invalid @enderror"
                                wire:model.blur="form.categoryExpenseId">
                                <option value="0">-- Sélectionner --</option>
                                @foreach ($this->categoryExpenses as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('form.categoryExpenseId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de Frais OU Source de Dépense (Optimisé avec wire:key) -->
                        <div class="col-md-6" wire:key="expense-type-field-{{ $expenseType }}">
                            @if ($expenseType === 'fee')
                                <label class="form-label fw-bold">
                                    <i class="bi bi-receipt me-1"></i>
                                    Type de Frais
                                </label>
                                <select class="form-select @error('form.categoryFeeId') is-invalid @enderror"
                                    wire:model.live="form.categoryFeeId">
                                    <option value="0">-- Sélectionner --</option>
                                    @foreach ($this->categoryFees as $fee)
                                        <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.categoryFeeId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <label class="form-label fw-bold">
                                    <i class="bi bi-box-seam me-1"></i>
                                    Source de Dépense
                                </label>
                                <select class="form-select @error('form.otherSourceExpenseId') is-invalid @enderror"
                                    wire:model.live="form.otherSourceExpenseId">
                                    <option value="0">-- Sélectionner --</option>
                                    @foreach ($this->otherSources as $source)
                                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                                    @endforeach
                                </select>
                                @error('form.otherSourceExpenseId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Boutons d'action en bas du offcanvas --}}
            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="offcanvas">
                    <i class="bi bi-x-circle me-1"></i>
                    Annuler
                </button>
                <button type="button" class="btn btn-primary flex-fill" wire:click="save" wire:loading.attr="disabled"
                    wire:target="save">
                    <i class="bi bi-check-circle me-1" wire:loading.remove wire:target="save"></i>
                    <span class="spinner-border spinner-border-sm me-1" wire:loading wire:target="save"></span>
                    <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Modifier' : 'Enregistrer' }}</span>
                    <span wire:loading wire:target="save">Enregistrement...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Offcanvas personnalisé pour formulaire de dépense */
        #expenseFormOffcanvas {
            width: 600px;
        }

        /* État de chargement */
        .opacity-50 {
            opacity: 0.5;
        }

        /* Amélioration responsive */
        @media (max-width: 768px) {
            #expenseFormOffcanvas {
                width: 100% !important;
            }
        }

        {{-- Styles dark mode intégrés dans themes/_dark.scss --}}
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('expenseSaved', (data) => {
                // Ferme le offcanvas seulement si l'enregistrement a réussi
                if (data[0] && data[0].type === 'success') {
                    const offcanvasElement = document.getElementById('expenseFormOffcanvas');
                    const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvasInstance) {
                        offcanvasInstance.hide();
                    }
                }
                // Si type === 'error', le offcanvas reste ouvert pour afficher les erreurs
            });
        });
    </script>
@endpush
