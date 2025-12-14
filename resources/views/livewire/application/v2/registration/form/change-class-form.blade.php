<div>
    {{-- Modal Changement de Classe --}}
    <x-modal.build-modal-fixed idModal="changeClassModal" size="md" bg="bg-warning text-dark"
        headerLabel="Changer de classe" headerLabelIcon="bi bi-arrow-left-right">

        @if ($registration)
            <form wire:submit="changeClass">
                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle fs-4 me-2"></i>
                        <div>
                            <strong>Élève:</strong> {{ $registration->student->name }}<br>
                            <strong>Classe actuelle:</strong>
                            <span class="badge bg-secondary">{{ $registration->classRoom->name }}</span><br>
                            <strong>Option:</strong> {{ $registration->classRoom->option->name }}<br>
                            <strong>Section:</strong> {{ $registration->classRoom->option->section->name }}
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Section --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-diagram-3"></i> Nouvelle Section
                        </label>
                        <select wire:model.live="sectionId" class="form-select">
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Option --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-bookmark"></i> Nouvelle Option
                        </label>
                        <select wire:model.live="optionId" class="form-select">
                            <option value="">Choisir une option</option>
                            @foreach ($options as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nouvelle Classe --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-door-closed"></i> Nouvelle Classe <span class="text-danger">*</span>
                        </label>
                        <select wire:model="newClassRoomId"
                            class="form-select @error('newClassRoomId') is-invalid @enderror" required>
                            <option value="">Choisir une classe</option>
                            @foreach ($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" @if ($registration->class_room_id == $classRoom->id) disabled @endif>
                                    {{ $classRoom->name }}
                                    @if ($registration->class_room_id == $classRoom->id)
                                        (Classe actuelle)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('newClassRoomId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-arrow-left-right me-2"></i> Changer de classe
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Changement en cours...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </x-modal.build-modal-fixed>

    @script
        <script>
            $wire.on('registration-updated', () => {
                bootstrap.Modal.getInstance(document.getElementById('changeClassModal'))?.hide();
            });
        </script>
    @endscript
</div>
