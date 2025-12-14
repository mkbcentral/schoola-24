<div>
    {{-- Offcanvas Bootstrap pour formulaire de classe --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="classRoomFormOffcanvas"
        aria-labelledby="classRoomFormOffcanvasLabel" data-bs-backdrop="true" data-bs-scroll="false" data-bs-keyboard="true"
        wire:ignore.self style="width: 500px">

        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="classRoomFormOffcanvasLabel">
                <i class="bi bi-{{ $classRoom ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                {{ $classRoom ? 'Modifier' : 'Nouvelle' }} Classe
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Nom de la classe -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-door-open me-1"></i>
                            Nom de la classe
                        </label>
                        <x-form.input type="text" wire:model.blur="form.name" error="form.name" icon="bi bi-door-open"
                            placeholder="Ex: 1ère A, 2ème B" list="classRoomSuggestions" />
                        <datalist id="classRoomSuggestions">
                            <option value="1ère">
                            <option value="2ème">
                            <option value="3ème">
                            <option value="4ème">
                            <option value="5ème">
                            <option value="6ème">
                            <option value="7ème">
                            <option value="8ème">
                        </datalist>
                        @error('form.name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Sélectionnez une suggestion ou saisissez un nom personnalisé
                        </div>
                    </div>

                    <!-- Option -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-diagram-3 me-1"></i>
                            Option
                        </label>
                        <select class="form-select @error('form.option_id') is-invalid @enderror" wire:model="form.option_id">
                            @foreach ($options as $option)
                                <option value="{{ $option->id }}">{{ $option->name }} ({{ $option->section->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('form.option_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Informations supplémentaires -->
                    @if ($classRoom)
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Mode édition:</strong> Vous modifiez une classe existante
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Boutons d'action -->
                <div class="d-grid gap-2 mt-4">
                    <x-form.button type="submit" class="btn-{{ $classRoom ? 'warning' : 'success' }} btn-lg"
                        wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-{{ $classRoom ? 'check-circle' : 'save' }} me-2"></i>
                            {{ $classRoom ? 'Mettre à jour' : 'Enregistrer' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Enregistrement...
                        </span>
                    </x-form.button>
                    <x-form.button type="button" class="btn-secondary" data-bs-dismiss="offcanvas">
                        <i class="bi bi-x-circle me-2"></i>
                        Annuler
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>
