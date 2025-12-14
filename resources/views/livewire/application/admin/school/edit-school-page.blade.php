<div>
    <x-navigation.bread-crumb icon='bi bi-pencil-square' label="MODIFIER L'ÉCOLE">
        <x-navigation.bread-crumb-item label='Modifier' />
        <x-navigation.bread-crumb-item label='Écoles' isLinked=true link="admin.schools.index" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="card card-outline card-warning p-4">
            <x-others.list-title title='Modifier l\'École: {{ $school->name }}' icon='bi bi-pencil-square' />

            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>

            <form wire:submit.prevent='update'>
                <div class="card mt-3">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Informations de l'École</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nom de l'école <span class="text-danger">*</span></label>
                                    <input type="text" wire:model='name' class="form-control @error('name') is-invalid @enderror" 
                                        id="name">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type d'école <span class="text-danger">*</span></label>
                                    <select wire:model='type' class="form-control @error('type') is-invalid @enderror" id="type">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Primaire">Primaire</option>
                                        <option value="Secondaire">Secondaire</option>
                                        <option value="Primaire et Secondaire">Primaire et Secondaire</option>
                                        <option value="Université">Université</option>
                                        <option value="Formation Professionnelle">Formation Professionnelle</option>
                                    </select>
                                    @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email de l'école <span class="text-danger">*</span></label>
                                    <input type="email" wire:model='email' class="form-control @error('email') is-invalid @enderror" 
                                        id="email">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model='phone' class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone">
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo de l'école</label>
                                    <input type="file" wire:model='logo' class="form-control @error('logo') is-invalid @enderror" 
                                        id="logo" accept="image/*">
                                    @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                                    
                                    @if($logo)
                                        <div class="mt-2">
                                            <img src="{{ $logo->temporaryUrl() }}" alt="Preview" width="100">
                                        </div>
                                    @elseif($school->logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo actuel" width="100">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Statut</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" wire:model='is_active'>
                                        <label class="custom-control-label" for="is_active">
                                            {{ $is_active ? 'Actif' : 'Inactif' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="button" wire:click='cancel' class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                        <i class="bi bi-save"></i> Mettre à Jour
                    </button>
                </div>
            </form>
        </div>
    </x-content.main-content-page>
</div>
