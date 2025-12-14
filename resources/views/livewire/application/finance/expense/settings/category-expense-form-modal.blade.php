<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="categoryFormOffcanvas"
        aria-labelledby="categoryFormOffcanvasLabel" data-bs-backdrop="true" data-bs-scroll="false" data-bs-keyboard="true"
        wire:ignore.self style="width: 500px">

        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="categoryFormOffcanvasLabel">
                <i class="bi {{ $isEditMode ? 'bi-pencil' : 'bi-plus-circle' }} me-2"></i>
                {{ $isEditMode ? 'Modifier la Catégorie' : 'Nouvelle Catégorie' }}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form wire:submit="save">
                <!-- Name Field -->
                <div class="mb-3">
                    <label for="categoryName" class="form-label" wire:ignore>
                        <i class="bi bi-tag me-1"></i>
                        Nom de la Catégorie <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('form.name') is-invalid @enderror"
                        id="categoryName" wire:model.blur="form.name" autocomplete="off"
                        placeholder="Ex: Fournitures scolaires, Électricité, etc.">
                    @error('form.name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info d-flex align-items-center" role="alert" wire:ignore>
                    <i class="bi bi-info-circle me-2"></i>
                    <small>
                        Cette catégorie sera utilisée pour organiser vos dépenses sur frais et autres dépenses.
                    </small>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="offcanvas">
                        <i class="bi bi-x-circle me-2"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi {{ $isEditMode ? 'bi-check-circle' : 'bi-plus-circle' }} me-2"></i>
                            {{ $isEditMode ? 'Modifier' : 'Créer' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            #categoryFormOffcanvas {
                width: 500px;
            }

            @media (max-width: 768px) {
                #categoryFormOffcanvas {
                    width: 100% !important;
                }
            }

            {{-- Styles dark mode intégrés dans themes/_dark.scss --}}
        </style>
    @endpush

    @script
        <script>
            const categoryOffcanvas = document.getElementById('categoryFormOffcanvas');
            const categoryOffcanvasInstance = new bootstrap.Offcanvas(categoryOffcanvas);

            // Close offcanvas on success
            $wire.on('close-category-modal', () => {
                categoryOffcanvasInstance.hide();
            });

            // Reset form when offcanvas is closed
            categoryOffcanvas.addEventListener('hidden.bs.offcanvas', function() {
                $wire.call('closeModal');
            });
        </script>
    @endscript

</div>
