<div>
    <x-modal.build-modal-fixed idModal='form-derogation' size='md' headerLabel="FAIRE UNE DÉROGATION"
        headerLabelIcon='bi bi-arrow-left-right'>
        <!-- Informations de l'élève -->
        <div class="mb-4 border rounded p-3">
            <h6 class="mb-2">Informations de l'élève</h6>
            <div><strong>Nom :</strong> {{ $registration->student->name ?? '-' }}</div>
            <div><strong>Classe :</strong> {{ $registration?->classRoom?->getOriginalClassRoomName() ?? '-' }}</div>
        </div>
        <form wire:submit.prevent="save" wire:loading.class="opacity-50" wire:target="save">
            <div class="modal-body">
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_month" wire:model.live="is_month">
                    <label class="form-check-label" for="is_month">Dérogation mensuelle</label>
                </div>
                @if ($is_month)
                    <div class="mb-3">
                        <label for="month_date" class="form-label">Mois concerné</label>
                        <input type="date" id="month_date" class="form-control" wire:model.defer="month_date">
                        @error('month_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="date" id="start_date" class="form-control" wire:model.defer="start_date">
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="date" id="end_date" class="form-control" wire:model.defer="end_date">
                        @error('end_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </x-modal.build-modal-fixed>
    <script>
        window.addEventListener('show-derogation-modal', () => {
            var myModal = new bootstrap.Modal(document.getElementById('derogationModal'));
            myModal.show();
        });
        window.addEventListener('hide-derogation-modal', () => {
            var myModal = bootstrap.Modal.getInstance(document.getElementById('derogationModal'));
            if (myModal) myModal.hide();
        });
    </script>
</div>
