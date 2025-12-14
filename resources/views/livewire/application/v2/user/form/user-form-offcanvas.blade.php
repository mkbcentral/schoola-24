<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUser" data-bs-backdrop="static" data-bs-keyboard="false"
        wire:ignore.self style="width: 600px;">
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title">
                <i class="bi bi-person-plus me-2"></i>
                {{ $user ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                wire:click="closeOffcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form.label for="name">
                            <i class="bi bi-person me-1"></i>Nom complet <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="text" id="name" wire:model="form.name" error="form.name"
                            icon="bi bi-person" placeholder="Ex: Jean Dupont" />
                        @error('form.name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <x-form.label for="username">
                            <i class="bi bi-at me-1"></i>Pseudo <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="text" id="username" wire:model="form.username" error="form.username"
                            icon="bi bi-at" placeholder="Ex: jdupont" />
                        @error('form.username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form.label for="email">
                            <i class="bi bi-envelope me-1"></i>Email
                        </x-form.label>
                        <x-form.input type="email" id="email" wire:model="form.email" error="form.email"
                            icon="bi bi-envelope" placeholder="Ex: jean.dupont@example.com" />
                        @error('form.email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <x-form.label for="phone">
                            <i class="bi bi-phone me-1"></i>Téléphone <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="text" id="phone" wire:model="form.phone" error="form.phone"
                            icon="bi bi-phone" placeholder="Ex: +243 900 000 000" />
                        @error('form.phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <x-form.label for="role_id">
                        <i class="bi bi-shield me-1"></i>Rôle <span class="text-danger">*</span>
                    </x-form.label>
                    <div class="input-group">
                        <span class="input-group-text bg-body border-secondary">
                            <i class="bi bi-shield"></i>
                        </span>
                        <select class="form-select border-secondary @error('form.role_id') is-invalid @enderror" id="role_id"
                            wire:model="form.role_id">
                        <option value="">-- Sélectionner un rôle --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                                ({{ $role->is_for_school ? 'École' : 'Application' }})
                            </option>
                        @endforeach
                        </select>
                    </div>
                    @error('form.role_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" wire:model="form.is_active">
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-check-circle me-1"></i>Compte actif
                        </label>
                    </div>
                </div>

                @if (!$user)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Le mot de passe par défaut est : <strong>password</strong></small>
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <x-form.button type="submit" class="btn-primary" wire:loading.attr="disabled"
                        wire:loading.class="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-save me-1"></i>
                            {{ $user ? 'Mettre à jour' : 'Enregistrer' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </span>
                            Enregistrement...
                        </span>
                    </x-form.button>
                    <x-form.button type="button" class="btn-outline-secondary" data-bs-dismiss="offcanvas"
                        wire:click="closeOffcanvas" wire:loading.attr="disabled">
                        <i class="bi bi-x-circle me-1"></i>
                        Annuler
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('show-user-offcanvas', () => {
        const offcanvasElement = document.getElementById('offcanvasUser');
        const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    });

    $wire.on('hide-user-offcanvas', () => {
        const offcanvasElement = document.getElementById('offcanvasUser');
        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (offcanvas) {
            offcanvas.hide();
        }
    });
</script>
@endscript
