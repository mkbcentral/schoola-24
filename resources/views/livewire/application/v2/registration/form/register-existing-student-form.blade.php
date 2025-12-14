<div>
    {{-- Modal Inscription Ancien Élève --}}
    <x-modal.build-modal-fixed idModal="registerExistingStudentModal" size="lg" headerLabel="Inscrire un ancien élève"
        headerLabelIcon="bi bi-person-check">

        <form wire:submit="register">
            <div class="row g-3">
                {{-- Sélection de l'élève --}}
                <div class="col-12">
                    <label class="form-label fw-bold">
                        <i class="bi bi-person"></i> Élève <span class="text-danger">*</span>
                    </label>
                    <x-v2.search-dropdown wire:model="form.studentId" :items="$students" error="form.studentId"
                        placeholder="Rechercher un élève..." emptyMessage="Aucun élève trouvé" />
                </div>

                {{-- Informations de l'élève sélectionné --}}
                @if ($selectedStudent)
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle fs-4 me-3"></i>
                                <div>
                                    <strong>Informations de l'élève:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Genre:
                                            <strong>{{ $selectedStudent->gender === 'M' ? 'Masculin' : 'Féminin' }}</strong>
                                        </li>
                                        <li>Âge: <strong>{{ $selectedStudent->getFormattedAg() }}</strong></li>
                                        @if ($lastClassRoom)
                                            <li>Dernière classe: <strong>{{ $lastClassRoom }}</strong></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Section --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-diagram-3"></i> Section <span class="text-danger">*</span>
                    </label>
                    <select wire:model.live="form.sectionId" class="form-select" required>
                        <option value="">Choisir...</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Option --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-bookmark"></i> Option <span class="text-danger">*</span>
                    </label>
                    <select wire:model.live="form.optionId" class="form-select" required @disabled(!$form->sectionId)>
                        <option value="">Choisir...</option>
                        @foreach ($options as $option)
                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-door-closed"></i> Classe <span class="text-danger">*</span>
                    </label>
                    <select wire:model.live="form.classRoomId"
                        class="form-select @error('form.classRoomId') is-invalid @enderror" required
                        @disabled(!$form->optionId)>
                        <option value="">Choisir...</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                        @endforeach
                    </select>
                    @error('form.classRoomId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Frais d'inscription --}}
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-cash-coin"></i> Frais d'inscription
                    </label>
                    <input type="text" class="form-control"
                        value="{{ $form->registrationFeeId ? 'Frais sélectionnés automatiquement' : 'Aucun' }}"
                        disabled>
                </div>

                {{-- Date d'inscription --}}
                <div class="col-12 col-md-6">
                    <x-form.input wire:model="form.registrationDate" type="date" label="Date d'inscription"
                        icon="bi-calendar-event" error="form.registrationDate" required />
                </div>

                {{-- Boutons d'action --}}
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <x-form.button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i> Annuler
                        </x-form.button>
                        <x-form.button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="bi bi-check-circle me-2"></i> Inscrire l'élève
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Inscription en cours...
                            </span>
                        </x-form.button>
                    </div>
                </div>
            </div>
        </form>
    </x-modal.build-modal-fixed>

    @script
        <script>
            $wire.on('registration-created', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('registerExistingStudentModal'));
                if (modal) modal.hide();
            });
        </script>
    @endscript
</div>
