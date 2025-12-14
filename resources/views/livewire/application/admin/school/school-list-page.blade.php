<div>
    <x-navigation.bread-crumb icon='bi bi-building' label="GESTION DES ÉCOLES">
        <x-navigation.bread-crumb-item label='Écoles' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="card card-outline card-indigo p-4">
            <x-others.list-title title='Liste des Écoles' icon='bi bi-buildings' />

            <!-- Statistiques -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total_schools'] }}</h3>
                            <p>Total des écoles</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['active_schools'] }}</h3>
                            <p>Écoles actives</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['inactive_schools'] }}</h3>
                            <p>Écoles inactives</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <x-form.search-input wire:model.live='q' placeholder="Rechercher une école..." />
                    @can('create', App\Models\School::class)
                        <x-form.app-button wire:click='createSchool' textButton='Nouvelle École' 
                            icon="bi bi-plus-circle" class="btn-primary" />
                    @endcan
                </div>

                <table class="table table-bordered table-hover table-sm">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center">#</th>
                            <th wire:click="sortData('name')" class="cursor-pointer">
                                <span>NOM DE L'ÉCOLE</span>
                                <x-form.sort-icon sortField="name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                            </th>
                            <th>TYPE</th>
                            <th>EMAIL</th>
                            <th>TÉLÉPHONE</th>
                            <th class="text-center">UTILISATEURS</th>
                            <th class="text-center">STATUT</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($schools->isEmpty())
                            <tr>
                                <td colspan="8"><x-errors.data-empty /></td>
                            </tr>
                        @else
                            @foreach ($schools as $index => $school)
                                <tr wire:key='school-{{ $school->id }}'>
                                    <td class="text-center">{{ $schools->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $school->name }}</strong>
                                        @if($school->logo)
                                            <img src="{{ asset('storage/' . $school->logo) }}" 
                                                alt="Logo" width="30" height="30" class="ml-2">
                                        @endif
                                    </td>
                                    <td>{{ $school->type }}</td>
                                    <td>{{ $school->email }}</td>
                                    <td>{{ $school->phone }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $school->users_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($school->app_status === 'active')
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('manageUsers', $school)
                                                <button wire:click='viewSchoolUsers({{ $school->id }})' 
                                                    class="btn btn-sm btn-info" title="Voir les utilisateurs">
                                                    <i class="bi bi-people"></i>
                                                </button>
                                            @endcan

                                            @can('update', $school)
                                                <button wire:click='editSchool({{ $school->id }})' 
                                                    class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            @endcan

                                            @can('toggleStatus', $school)
                                                <button wire:click='toggleSchoolStatus({{ $school->id }})' 
                                                    class="btn btn-sm btn-secondary" 
                                                    title="Activer/Désactiver"
                                                    wire:confirm="Êtes-vous sûr de vouloir changer le statut?">
                                                    <i class="bi bi-toggle-on"></i>
                                                </button>
                                            @endcan

                                            @can('delete', $school)
                                                <button wire:click='deleteSchool({{ $school->id }})' 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Supprimer"
                                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette école?">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $schools->links() }}
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
