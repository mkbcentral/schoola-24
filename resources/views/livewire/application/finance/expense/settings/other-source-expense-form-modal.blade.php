<div class="offcanvas offcanvas-end" tabindex="-1" id="sourceFormOffcanvas" aria-labelledby="sourceFormOffcanvasLabel"
    data-bs-backdrop="true" data-bs-scroll="false" data-bs-keyboard="true" wire:ignore.self style="width: 500px">

    <div class="offcanvas-header bg-success text-white">
        <h5 class="offcanvas-title" id="sourceFormOffcanvasLabel">
            <i class="bi {{ $isEditMode ? 'bi-pencil' : 'bi-plus-circle' }} me-2"></i>
            {{ $isEditMode ? 'Modifier la Source' : 'Nouvelle Source' }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <form wire:submit="save">
            <!-- Name Field -->
            <div class="mb-3">
                <label for="sourceName" class="form-label" wire:ignore>
                    <i class="bi bi-folder2-open me-1"></i>
                    Nom de la Source <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('form.name') is-invalid @enderror" id="sourceName"
                    wire:model.blur="form.name" autocomplete="off"
                    placeholder="Ex: Dons, Subventions, Partenaires, etc.">
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
                    Cette source sera utilisée pour identifier l'origine des autres dépenses.
                </small>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="offcanvas">
                    <i class="bi bi-x-circle me-2"></i>
                    Annuler
                </button>
                <button type="submit" class="btn btn-success flex-fill" wire:loading.attr="disabled">
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
        #sourceFormOffcanvas {
            width: 500px;
        }

        @media (max-width: 768px) {
            #sourceFormOffcanvas {
                width: 100% !important;
            }
        }

        {{-- Styles dark mode intégrés dans themes/_dark.scss --}}
    </style>
@endpush

@script
    <script>
        const sourceOffcanvas = document.getElementById('sourceFormOffcanvas');
        const sourceOffcanvasInstance = new bootstrap.Offcanvas(sourceOffcanvas);

        // Close offcanvas on success
        $wire.on('close-source-modal', () => {
            sourceOffcanvasInstance.hide();
        });

        // Reset form when offcanvas is closed
        sourceOffcanvas.addEventListener('hidden.bs.offcanvas', function() {
            $wire.call('closeModal');
        });
    </script>
@endscript
