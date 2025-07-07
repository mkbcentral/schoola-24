<div>
    <x-navigation.bread-crumb icon='bi bi-person-vcard-fill' label="Gestionnaire inscriptions">
        <x-navigation.bread-crumb-item label='Gestionnaire inscriptions' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row mt-4">
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3 w-100">
                            <label for="responsible_student_id" class="form-label fw-semibold">Responsable</label>
                            <div class="d-flex align-items-center">
                                <x-widget.data.list-resp-select-widget wire:model="responsible_student_id"
                                    error="responsible_student_id" id="responsible_student_id" />
                                <x-form.app-button wire:click='openNewResponsibleStudent' data-bs-toggle="modal"
                                    data-bs-target="#form-responsible-student" textButton='Nouveau'
                                    icon="bi bi-plus-circle" class="btn-primary ms-2" />
                            </div>
                        </div>
                        <livewire:application.registration.form.new-registration-form />
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <livewire:application.registration.list.list-registration-by-date-page>
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
    <livewire:application.student.form.form-responsible-student-page />
</div>
