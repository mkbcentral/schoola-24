@props(['form', 'responsibleStudents'])

<div class="step-content">
    <h6 class="fw-bold mb-3 text-success">
        <i class="bi bi-1-circle-fill me-2"></i> Informations de l'élève
    </h6>

    <div class="row">
        <div class="col-md-7 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-person"></i> Nom complet <span class="text-danger">*</span>
            </label>
            <x-form.input type="text" wire:model="form.name" error="form.name" placeholder="Ex: Jean Dupont"
                required />
            @error('form.name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-5 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-gender-ambiguous"></i> Genre <span class="text-danger">*</span>
            </label>
            <x-v2.gender-selector wire:model="form.gender" error="form.gender" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-geo-alt"></i> Lieu de naissance <span class="text-danger">*</span>
            </label>
            <x-form.input type="text" wire:model="form.placeOfBirth" error="form.placeOfBirth"
                placeholder="Ex: Kinshasa" required />
            @error('form.placeOfBirth')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-5 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-calendar-event"></i> Date naissance <span class="text-danger">*</span>
            </label>
            <x-form.input type="date" wire:model="form.dateOfBirth" error="form.dateOfBirth"
                max="{{ date('Y-m-d') }}" required />
            @error('form.dateOfBirth')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">
            <i class="bi bi-person-badge"></i> Responsable
        </label>
        <x-v2.search-dropdown wire:model="form.responsibleStudentId" :items="$responsibleStudents" error="form.responsibleStudentId"
            placeholder="Rechercher un responsable (optionnel)..." emptyMessage="Aucun responsable trouvé" />
    </div>

    <div class="d-flex justify-content-between gap-2 mt-4">
        <x-form.button type="button" class="btn-outline-secondary flex-fill" data-bs-dismiss="offcanvas">
            Annuler
        </x-form.button>
        <x-form.button type="submit" class="btn-success flex-fill" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="nextStep">
                Suivant <i class="bi bi-arrow-right ms-2"></i>
            </span>
            <span wire:loading wire:target="nextStep">
                <i class="bi bi-hourglass-split me-2"></i> Validation...
            </span>
        </x-form.button>
    </div>
</div>
