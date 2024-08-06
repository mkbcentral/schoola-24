<div>
    <x-navigation.bread-crumb icon='bi bi-house-gear-fill' label="Gestionnaire es écoles" color=''>
        <x-navigation.bread-crumb-item label='Gestionnaire écoles' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div>
            <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-2 mt-2">
                @foreach ($schoools as $school)
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img class="img-fluid rounded-circle me-2" style="width: 50px"
                                        src="{{ asset('images/defautl-user.jpg') }}" alt="User Image">
                                    <h3 class="">{{ $school->name }}</h3>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <span class="h5 text-uppercase">Pending</span>
                                    <x-others.dropdown icon="bi bi-sliders" class="btn-light">
                                        <x-others.dropdown-link iconLink='bi bi-box-fill' labelText='Configuration'
                                            class="text-secondary"
                                            href="{{ route('admin.school.configure', $school) }}" />
                                    </x-others.dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-content.main-content-page>
</div>
