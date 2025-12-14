<div>
    {{-- Offcanvas --}}
    <div class="offcanvas offcanvas-end offcanvas-large"
         tabindex="-1"
         id="schoolFormOffcanvas"
         data-bs-backdrop="static"
         wire:ignore.self
         style="width: 600px;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">
                <i class="bi bi-building me-2"></i>
                {{ $school ? 'Modifier l\'école' : 'Nouvelle école' }}
            </h5>
            <button type="button"
                    class="btn-close"
                    wire:click="closeOffcanvas"
                    aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                {{-- Nom et Type sur la même ligne --}}
                <div class="row mb-3">
                    <div class="col-md-7">
                        <x-form.label for="school-name">
                            <i class="bi bi-building me-1"></i>Nom de l'école <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="text" id="school-name" wire:model="form.name" error="form.name"
                            placeholder="Ex: École Primaire Saint-Joseph" />
                        @error('form.name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <x-form.label for="school-type">
                            <i class="bi bi-tag me-1"></i>Type <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="text" id="school-type" wire:model="form.type" error="form.type"
                            icon="bi bi-tag" placeholder="Cliquez pour choisir"
                            data-bs-toggle="popover"
                            data-bs-placement="bottom"
                            data-bs-trigger="click"
                            data-bs-html="true" />
                        @error('form.type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Email et Téléphone sur la même ligne --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form.label for="school-email">
                            <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="email" id="school-email" wire:model="form.email" error="form.email"
                            icon="bi bi-envelope" placeholder="ecole@example.com" />
                        @error('form.email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <x-form.label for="school-phone">
                            <i class="bi bi-telephone me-1"></i>Téléphone <span class="text-danger">*</span>
                        </x-form.label>
                        <x-form.input type="text" id="school-phone" wire:model="form.phone" error="form.phone"
                            icon="bi bi-telephone" placeholder="+243 123 456 789" />
                        @error('form.phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Adresse --}}
                <div class="mb-3">
                    <x-form.label for="school-address">
                        <i class="bi bi-geo-alt me-1"></i>Adresse
                    </x-form.label>
                    <div class="input-group">
                        <span class="input-group-text bg-body border-secondary">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <textarea class="form-control border-secondary @error('form.address') is-invalid @enderror"
                                  id="school-address"
                                  wire:model="form.address"
                                  rows="2"
                                  placeholder="Adresse complète de l'école"></textarea>
                    </div>
                    @error('form.address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Logo Section --}}
                @if($school)
                    <div class="mb-3">
                        <x-form.label>
                            <i class="bi bi-image me-1"></i>Logo de l'école
                        </x-form.label>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    @if($currentLogo)
                                        <img src="{{ asset('storage/' . $currentLogo) }}" 
                                             alt="Logo" 
                                             class="rounded border"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center"
                                             style="width: 80px; height: 80px;">
                                            <i class="bi bi-image text-muted fs-2"></i>
                                        </div>
                                    @endif
                                    <div class="grow">
                                        <p class="mb-2 small text-muted">
                                            {{ $currentLogo ? 'Logo actuel' : 'Aucun logo défini' }}
                                        </p>
                                        <div class="d-flex gap-2">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#logoModal">
                                                <i class="bi bi-cloud-upload me-1"></i>
                                                {{ $currentLogo ? 'Changer' : 'Ajouter' }}
                                            </button>
                                            @if($currentLogo)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        wire:click="removeLogo"
                                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce logo?">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Supprimer
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Statuts --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <x-form.label for="app-status">
                            <i class="bi bi-toggle-on me-1"></i>Statut Application
                        </x-form.label>
                        <div class="input-group">
                            <span class="input-group-text bg-body border-secondary">
                                <i class="bi bi-toggle-on"></i>
                            </span>
                            <select class="form-select border-secondary"
                                    id="app-status"
                                    wire:model="form.app_status">
                                <option value="">Sélectionner un statut</option>
                                @foreach(App\Enums\SchoolAppEnum::getValues() as $status)
                                    <option value="{{ $status }}">
                                        {{ $status === App\Enums\SchoolAppEnum::IS_FREE ? 'Gratuit' : 'Abonné' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form.label for="school-status">
                            <i class="bi bi-check-circle me-1"></i>Statut École
                        </x-form.label>
                        <div class="input-group">
                            <span class="input-group-text bg-body border-secondary">
                                <i class="bi bi-check-circle"></i>
                            </span>
                            <select class="form-select border-secondary"
                                    id="school-status"
                                    wire:model="form.school_status">
                                <option value="">Sélectionner un statut</option>
                                @foreach(App\Enums\SchoolEnum::getValues() as $status)
                                    <option value="{{ $status }}">
                                        @if($status === App\Enums\SchoolEnum::PENDING)
                                            En attente
                                        @elseif($status === App\Enums\SchoolEnum::APPROVED)
                                            Approuvé
                                        @elseif($status === App\Enums\SchoolEnum::REJECTED)
                                            Rejeté
                                        @elseif($status === App\Enums\SchoolEnum::SUSPENDED)
                                            Suspendu
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Alert Info --}}
                <div class="alert alert-info d-flex align-items-start" role="alert">
                    <i class="bi bi-info-circle me-2 mt-1"></i>
                    <div>
                        <small>
                            Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires.
                        </small>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-grid gap-2">
                    <x-form.button type="submit" class="btn-primary" wire:loading.attr="disabled"
                        wire:loading.class="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ $school ? 'Mettre à jour' : 'Enregistrer' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Enregistrement...
                        </span>
                    </x-form.button>
                    <x-form.button type="button" class="btn-outline-secondary" wire:click="closeOffcanvas"
                        wire:loading.attr="disabled">
                        <i class="bi bi-x-circle me-1"></i>
                        Annuler
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>

    {{-- Logo Upload Modal --}}
    <div class="modal fade" id="logoModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="bi bi-image me-2"></i>
                        Mettre à jour le logo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        @if($logo)
                            <div class="mb-3">
                                <img src="{{ $logo->temporaryUrl() }}" 
                                     alt="Aperçu" 
                                     class="rounded border shadow-sm"
                                     style="max-width: 100%; max-height: 300px; object-fit: contain;">
                            </div>
                        @else
                            <div class="border-2 border-dashed rounded p-5 mb-3"
                                 style="border-style: dashed !important;">
                                <i class="bi bi-cloud-upload text-primary" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Cliquez sur le bouton ci-dessous pour sélectionner une image
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <label for="logo-input" class="btn btn-outline-primary">
                            <i class="bi bi-folder2-open me-2"></i>
                            {{ $logo ? 'Changer l\'image' : 'Parcourir' }}
                        </label>
                        <input type="file" 
                               id="logo-input" 
                               wire:model="logo" 
                               accept="image/*"
                               class="d-none">
                        
                        @error('logo')
                            <div class="alert alert-danger mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div wire:loading wire:target="logo" class="alert alert-info mb-0">
                            <i class="bi bi-hourglass-split me-2"></i>
                            Téléchargement en cours...
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Format accepté: JPG, PNG, GIF. Taille max: 2 Mo</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Annuler
                    </button>
                    <button type="button" 
                            class="btn btn-primary"
                            wire:click="saveLogo"
                            wire:loading.attr="disabled"
                            :disabled="!$logo">
                        <span wire:loading.remove wire:target="saveLogo">
                            <i class="bi bi-check-circle me-1"></i>
                            Enregistrer
                        </span>
                        <span wire:loading wire:target="saveLogo">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const offcanvasElement = document.getElementById('schoolFormOffcanvas');
    const offcanvas = new bootstrap.Offcanvas(offcanvasElement);

    $wire.on('show-school-offcanvas', () => {
        offcanvas.show();
        
        // Initialize popover on type input with interactive content
        setTimeout(() => {
            const typeInput = document.getElementById('school-type');
            if (typeInput) {
                const types = {
                    'C.S': 'Complexe Scolaire',
                    'Collège': 'Collège',
                    'Lycée': 'Lycée',
                    'Institut': 'Institut'
                };
                
                // Create popover content with clickable buttons
                let content = '<div class="d-grid gap-2">';
                for (const [value, label] of Object.entries(types)) {
                    content += `
                        <button type="button" class="btn btn-sm btn-outline-primary text-start school-type-btn" data-type="${value}">
                            <strong>${value}</strong>
                            <small class="d-block text-muted">${label}</small>
                        </button>
                    `;
                }
                content += '</div>';
                
                const popover = new bootstrap.Popover(typeInput, {
                    content: content,
                    html: true,
                    sanitize: false
                });
                
                // Handle button clicks in popover
                typeInput.addEventListener('shown.bs.popover', () => {
                    document.querySelectorAll('.school-type-btn').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            const selectedType = e.currentTarget.dataset.type;
                            $wire.set('form.type', selectedType);
                            popover.hide();
                        });
                    });
                });
            }
        }, 100);
    });

    $wire.on('hide-school-offcanvas', () => {
        offcanvas.hide();
        
        // Dispose popover
        const typeInput = document.getElementById('school-type');
        if (typeInput) {
            const popover = bootstrap.Popover.getInstance(typeInput);
            if (popover) popover.dispose();
        }
    });

    // Show logo modal directly
    $wire.on('show-logo-modal', () => {
        const logoModalElement = document.getElementById('logoModal');
        const logoModal = new bootstrap.Modal(logoModalElement);
        logoModal.show();
    });

    // Hide logo modal on success
    $wire.on('hide-logo-modal', () => {
        const logoModal = bootstrap.Modal.getInstance(document.getElementById('logoModal'));
        if (logoModal) {
            logoModal.hide();
        }
    });
</script>
@endscript
