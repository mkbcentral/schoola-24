<div>
    <x-navigation.bread-crumb icon='bi bi-house-gear-fill' label="Gestionnaire es écoles" color=''>
        <x-navigation.bread-crumb-item label='Gestionnaire écoles' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div>
            <div class="d-flex justify-content-end">
                <x-form.app-button wire:click='openFormSchoolModal' data-bs-toggle="modal" data-bs-target="#form-school"
                    textButton='Nouvelle école' icon="bi bi-house-add-fill" class="app-btn" />
            </div>
            <div class="row row-cols-2 row-cols-lg-3 g-3 g-lg-3 mt-2">
                @foreach ($schoools as $school)
                    <div class="col">
                        <div class="card card-link">
                            <div class="card-body bg-app rounded">
                                <div class="d-flex align-items-center">
                                    <img class="img-fluid rounded-circle me-2" style="width: 70px;height: 70px;"
                                        src="{{ asset($school->logo == null ? 'images/defautl-user.jpg' : 'storage/' . $school->logo) }}"
                                        alt="User Image">
                                    <h5>{{ $school->name }}</h5>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <span
                                        class="badge text-bg-info text-uppercase">{{ $school->school_status }}</span>|<span
                                        class=" badge text-bg-warning text-uppercase">{{ $school->app_status }}</span>
                                    <x-others.dropdown icon="bi bi-sliders" class="btn-light">
                                        <x-others.dropdown-link iconLink='bi bi-box-fill' labelText='Configuration'
                                            class="text-secondary"
                                            href="{{ route('admin.school.configure', $school) }}" />
                                        <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                            data-bs-toggle="modal" data-bs-target="#form-school" class="text-secondary"
                                            wire:click='edit({{ $school }})' href="#" />
                                    </x-others.dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <livewire:application.admin.form.form-school-page />
    </x-content.main-content-page>
</div>
