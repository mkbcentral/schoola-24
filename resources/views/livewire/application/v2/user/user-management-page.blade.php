<div>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>
                    Gestion des utilisateurs et rôles
                </h2>
                <p class="text-muted">Gérez les utilisateurs et les rôles du système</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        @if ($statistics)

            <div class="row mb-4">
                <x-v2.mini-stat-card title="Total utilisateurs" :value="$statistics['users']['total'] ?? 0" icon="bi-people" color="primary" />
                <x-v2.mini-stat-card title="Utilisateurs actifs" :value="$statistics['users']['active'] ?? 0" icon="bi-person-check"
                    color="success" />
                <x-v2.mini-stat-card title="En ligne" :value="$statistics['users']['online'] ?? 0" icon="bi-person-fill-check" color="info" />
                <x-v2.mini-stat-card title="Total rôles" :value="$statistics['roles']['total'] ?? 0" icon="bi-shield" color="warning" />
            </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="userManagementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'users')" wire:loading.attr="disabled" type="button">
                    <i class="bi bi-people me-1"></i>
                    Utilisateurs
                    <span wire:loading wire:target="activeTab" class="spinner-border spinner-border-sm ms-1"></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'roles' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'roles')" type="button">
                    <i class="bi bi-shield me-1"></i>
                    Rôles
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Users Tab -->
            <div class="tab-pane fade {{ $activeTab === 'users' ? 'show active' : '' }}" wire:key="tab-users">
                @include('livewire.application.v2.user.partials.user-list-refactored')
            </div>

            <!-- Roles Tab -->
            <div class="tab-pane fade {{ $activeTab === 'roles' ? 'show active' : '' }}" wire:key="tab-roles">
                @include('livewire.application.v2.user.partials.role-list-refactored')
            </div>
        </div>
    </div>

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />

    <!-- Include all form offcanvas components -->
    @livewire('application.v2.user.form.user-form-offcanvas', [], key('user-form'))
    @livewire('application.v2.user.form.role-form-offcanvas', [], key('role-form'))
</div>

@script
<script>
    $wire.on('success-message', (event) => {
            const message = event.message || 'Opération réussie !';
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                theme: 'auto',
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        });

</script>
@endscript
