@props(['form', 'sections', 'options', 'classRooms', 'createdAt'])

<div class="step-content">
    <h6 class="fw-bold mb-3 text-success">
        <i class="bi bi-2-circle-fill me-2"></i> Informations d'inscription
    </h6>

    {{-- Récapitulatif élève --}}
    <div class="alert alert-success mb-3">
        <div class="d-flex align-items-start">
            <i class="bi bi-check-circle fs-4 me-2"></i>
            <div class="w-100">
                <strong>Élève à inscrire:</strong>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div><strong>Nom:</strong> {{ $form->name }}</div>
                        <div><strong>Genre:</strong>
                            {{ $form->gender === 'M' ? 'Masculin' : 'Féminin' }}
                        </div>
                        <div><strong>Date naissance:</strong> {{ $form->dateOfBirth }}</div>
                    </div>
                    @if ($this->selectedResponsible)
                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                            <div class="vr h-100"></div>
                        </div>
                        <div class="col-md-5 text-muted">
                            <div>
                                <i class="bi bi-person-badge me-1"></i>
                                <strong>Responsable:</strong>
                            </div>
                            <div class="mt-1">{{ $this->selectedResponsible->name }}</div>
                            @if ($this->selectedResponsible->phone)
                                <div class="mt-1">
                                    <i class="bi bi-telephone me-1"></i> {{ $this->selectedResponsible->phone }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-diagram-3"></i> Section <span class="text-danger">*</span>
            </label>
            <select wire:model.live="form.sectionId" class="form-select border-secondary" required>
                <option value="">Choisir une section</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-bookmark"></i> Option <span class="text-danger">*</span>
            </label>
            <select wire:model.live="form.optionId" class="form-select border-secondary" required
                @disabled(!$form->sectionId)>
                <option value="">Choisir une option</option>
                @foreach ($options as $option)
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-door-closed"></i> Classe <span class="text-danger">*</span>
            </label>
            <select wire:model.live="form.classRoomId"
                class="form-select border-secondary @error('form.classRoomId') is-invalid @enderror" required
                @disabled(!$form->optionId)>
                <option value="">Choisir une classe</option>
                @foreach ($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                @endforeach
            </select>
            @error('form.classRoomId')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">
                <i class="bi bi-calendar-event"></i> Date inscription <span class="text-danger">*</span>
            </label>
            <x-form.input type="date" wire:model="createdAt" error="createdAt" required />
            @error('createdAt')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-grid gap-2 mt-4">
        <div class="d-flex justify-content-between gap-2">
            <x-form.button type="button" class="btn-outline-secondary" wire:click="previousStep">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </x-form.button>
            <x-form.button type="submit" class="btn-success" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <i class="bi bi-check-circle me-2"></i> Finaliser l'inscription
                </span>
                <span wire:loading>
                    <i class="bi bi-hourglass-split me-2"></i> Inscription en cours...
                </span>
            </x-form.button>
        </div>
    </div>
</div>
