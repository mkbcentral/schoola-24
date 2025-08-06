<div>
    <x-navigation.bread-crumb icon='bi bi-person-vcard-fill' label="Gestionnaire inscriptions">
        <x-navigation.bread-crumb-item label='Gestionnaire inscriptions' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body d-grid gap-2">
                        <x-form.app-button wire:click='openNewResponsibleStudent' data-bs-toggle="modal"
                            data-bs-target="#form-responsible-student" textButton='Nouveau responsable'
                            icon="bi bi-person-plus"
                            class="btn-outline-primary fw-semibold py-2 rounded-pill shadow-sm w-100" />
                        <a href="{{ route('registration.day') }}"
                            class="btn btn-outline-secondary fw-semibold py-2 rounded-pill shadow-sm w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-text me-2"></i> Liste des inscriptions/jour
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="btn-group w-100 shadow-sm rounded-pill overflow-hidden" role="group"
                                aria-label="Type de dossier">
                                <button type="button"
                                    class="btn rounded-0 rounded-start-pill fw-semibold px-4 py-2 @if ($caseType === 'new') main-bg shadow-sm @else btn-outline-primary bg-white @endif"
                                    wire:click="$set('caseType', 'new')">
                                    <i class="bi bi-plus-circle me-1"></i> Nouveau cas
                                </button>
                                <button type="button"
                                    class="btn rounded-0 rounded-end-pill fw-semibold px-4 py-2 @if ($caseType === 'existing') main-bg shadow-sm @else btn-outline-secondary bg-white @endif"
                                    wire:click="$set('caseType', 'existing')">
                                    <i class="bi bi-folder2-open me-1"></i> Ancien cas
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border spinner-border-sm text-warning" role="status" wire:loading>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        @if ($caseType === 'new')
                            <livewire:application.registration.form.new-registration-form />
                        @else
                            <livewire:application.registration.form.old-student-registration-form />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
    <livewire:application.student.form.form-responsible-student-page />
    @push('js')
        <script type="module">
            console.log('Main Registration Page Loaded');
        </script>
    @endpush
</div>
