<div>
    <x-navigation.bread-crumb icon='bi bi-building-add' label="CRÉER UNE ÉCOLE">
        <x-navigation.bread-crumb-item label='Nouvelle École' />
        <x-navigation.bread-crumb-item label='Écoles' isLinked=true link="admin.schools.index" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="card card-outline card-primary p-4">
            <x-others.list-title title='Créer une Nouvelle École' icon='bi bi-building-add' />

            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>

            @if($showSuccessMessage)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h5><i class="bi bi-check-circle"></i> École créée avec succès!</h5>
                    <p>Mot de passe temporaire de l'administrateur : <strong>{{ $tempPassword }}</strong></p>
                    <p class="mb-0">Veuillez noter ce mot de passe. Un email a été envoyé à l'administrateur.</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form wire:submit.prevent='save'>
                <!-- Informations de l'école -->
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Informations de l'École</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nom de l'école <span class="text-danger">*</span></label>
                                    <input type="text" wire:model='name' class="form-control @error('name') is-invalid @enderror" 
                                        id="name" placeholder="Nom de l'école">
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
                                        id="email" placeholder="email@ecole.com">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model='phone' class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone" placeholder="+243 XXX XXX XXX">
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="logo">Logo de l'école (optionnel)</label>
                                    <input type="file" wire:model='logo' class="form-control @error('logo') is-invalid @enderror" 
                                        id="logo" accept="image/*">
                                    @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                                    @if($logo)
                                        <div class="mt-2">
                                            <img src="{{ $logo->temporaryUrl() }}" alt="Preview" width="100">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de l'administrateur -->
                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Administrateur de l'École</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_name">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" wire:model='admin_name' 
                                        class="form-control @error('admin_name') is-invalid @enderror" 
                                        id="admin_name" placeholder="Nom de l'administrateur">
                                    @error('admin_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_username">Nom d'utilisateur <span class="text-danger">*</span></label>
                                    <input type="text" wire:model='admin_username' 
                                        class="form-control @error('admin_username') is-invalid @enderror" 
                                        id="admin_username" placeholder="Nom d'utilisateur">
                                    @error('admin_username') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_email">Email <span class="text-danger">*</span></label>
                                    <input type="email" wire:model='admin_email' 
                                        class="form-control @error('admin_email') is-invalid @enderror" 
                                        id="admin_email" placeholder="email@admin.com">
                                    @error('admin_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_phone">Téléphone</label>
                                    <input type="text" wire:model='admin_phone' 
                                        class="form-control @error('admin_phone') is-invalid @enderror" 
                                        id="admin_phone" placeholder="+243 XXX XXX XXX">
                                    @error('admin_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-2">
                            <i class="bi bi-info-circle"></i> 
                            Un mot de passe temporaire sera généré automatiquement et envoyé par email à l'administrateur.
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="button" wire:click='cancel' class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <i class="bi bi-save"></i> Créer l'École
                    </button>
                </div>
            </form>
        </div>
    </x-content.main-content-page>
</div>

@push('scripts')
<script>
    // Rediriger après succès
    window.addEventListener('redirect-after-success', () => {
        setTimeout(() => {
            window.location.href = '{{ route("admin.schools.index") }}';
        }, 5000);
    });
</script>
@endpush
