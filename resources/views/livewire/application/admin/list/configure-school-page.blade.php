<div>
    <x-navigation.bread-crumb icon='bi bi-box-fill' label="Configuration école" color=''>
        <x-navigation.bread-crumb-item label='Gestionnaire écoles' />
        <x-navigation.bread-crumb-item label='Liste des écoles' isLinked=true link="admin.schools" />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="d-flex justify-content-between">
            <div>
                <img class="img-fluid rounded-circle me-2" style="width: 70px;height: 70px;"
                    src="{{ asset($school->logo == null ? 'images/defautl-user.jpg' : 'storage/' . $school->logo) }}"
                    alt="User Image">
                <h2 id="schoolNameTop" class="mb-3">{{ $school->name }}</h2>
                <p class="mb-1">
                    <i class="bi bi-telephone-fill me-2"></i>
                    <span id="schoolPhone">{{ $school->phone }}</span>
                </p>
                <div class="d-flex align-items-center">
                    <p class="mb-1">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <span id="schoolEmail">{{ $school->email }}</span>
                    </p>
                    <x-form.app-button wire:click='edit' data-bs-toggle="modal" data-bs-target="#form-school"
                        icon="bi bi-pencil-square" class="" />
                </div>
            </div>
        </div>
        <div>
            @if ($user != null)
                <div>
                    <h2>Infos admin</h2>
                    <p class="mb-1">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <span id="schoolPhone">{{ $user->name }}</span>
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <span id="schoolPhone">{{ $user->email }}</span>
                    </p>
                    <div class="d-flex align-items-center">
                        <x-form.app-button wire:click='newUserAdmin' textButton="Envoyer mail du compte"
                            data-bs-toggle="modal" data-bs-target="#form-user-config" icon="bi bi-envelope-arrow-up"
                            class="app-btn mt-2 me-2" />
                        <x-others.dropdown icon="bi bi-sliders" class="btn-link">
                            <x-others.dropdown-link iconLink='bi bi-link' labelText='Attacher un simple menu'
                                href="{{ route('admin.attach.single.menu', $user) }}" class="" />
                            <x-others.dropdown-link iconLink='bi bi-link' labelText='Attacher un multi menu'
                                href="{{ route('admin.attach.multi.menu', $user) }}" class="" />
                            <x-others.dropdown-link iconLink='bi bi-link' labelText='Attacher un sous menu'
                                href="{{ route('admin.attach.sub.menu', $user) }}" class="" />
                        </x-others.dropdown>
                    </div>
                </div>
            @else
                <x-form.app-button wire:click='newUserAdmin' textButton="Ouvrir compte admin" data-bs-toggle="modal"
                    data-bs-target="#form-user-admin" icon="bi bi-person-fill-gear" class="app-btn mt-2" />
            @endif
        </div>
        <livewire:application.admin.form.form-school-page />
        <livewire:application.admin.form.form-user-admin-page />
    </x-content.main-content-page>
</div>
