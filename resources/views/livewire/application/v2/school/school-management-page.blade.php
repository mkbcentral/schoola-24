<div>
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-building' label="Gestion des Écoles">
        <x-navigation.bread-crumb-item label='Écoles' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        {{-- Statistics Cards --}}
        <div class="row g-3 mb-4">
            <x-v2.mini-stat-card
                title="Total des écoles"
                :value="$stats['total_schools']"
                icon="bi-building"
                color="primary" />

            <x-v2.mini-stat-card
                title="Écoles actives"
                :value="$stats['active_schools']"
                icon="bi-check-circle"
                color="success" />

            <x-v2.mini-stat-card
                title="Écoles inactives"
                :value="$stats['inactive_schools']"
                icon="bi-x-circle"
                color="warning" />

            <x-v2.mini-stat-card
                title="Utilisateurs"
                :value="$stats['total_users'] ?? 0"
                icon="bi-people"
                color="info" />
        </div>

        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-buildings text-primary me-2"></i>
                    Liste des Écoles
                </h4>
                <p class="text-muted mb-0">
                    <small>{{ $schools->total() }} école(s) trouvée(s)</small>
                </p>
            </div>
            <div class="d-flex gap-2">
                @can('create', App\Models\School::class)
                    <button wire:click="openCreateSchool" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Nouvelle École
                    </button>
                @endcan
            </div>
        </div>

        {{-- Search and Filter Bar --}}
        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
            <x-v2.search-bar 
                placeholder="Rechercher par nom, email ou téléphone..." 
                model="search"
                :searchTerm="$search"
                :resultCount="$schools->total()" />

            <div class="d-flex gap-2">
                {{-- Sort Button --}}
                <button wire:click="sortData('name')" 
                    class="btn btn-outline-secondary" 
                    title="Trier par nom">
                    <i class="bi bi-sort-alpha-{{ $sortBy === 'name' && $sortAsc ? 'down' : 'up' }} me-1"></i>
                    Trier
                </button>

                {{-- Reset Filters --}}
                @if($search)
                    <button wire:click="resetFilters"
                        class="btn btn-outline-danger"
                        title="Réinitialiser les filtres">
                        <i class="bi bi-x-circle me-1"></i>
                        Réinitialiser
                    </button>
                @endif
            </div>
        </div>
        {{-- Schools Cards Grid --}}
        <div class="row g-4" wire:loading.remove>
            @forelse ($schools as $school)
                <div class="col-md-6 col-lg-4" wire:key="school-{{ $school->id }}">
                    <x-v2.school-card :school="$school" />
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">Aucune école trouvée</h5>
                            @if($search)
                                <p class="text-muted">Essayez d'ajuster vos critères de recherche</p>
                                <button wire:click="resetFilters" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Réinitialiser la recherche
                                </button>
                            @else
                                <p class="text-muted">Commencez par créer votre première école</p>
                                @can('create', App\Models\School::class)
                                    <button wire:click="openCreateSchool" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        Créer une École
                                    </button>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($schools->hasPages())
            <div class="mt-4">
                {{ $schools->links() }}
            </div>
        @endif
    </x-content.main-content-page>

    {{-- School Form Offcanvas --}}
    @livewire('application.v2.school.form.school-form-offcanvas')

    {{-- Loading Overlay --}}
    <x-v2.loading-overlay 
        title="Chargement en cours..." 
        subtitle="Veuillez patienter"
        wire:loading.delay.long="openCreateSchool,editSchool,deleteSchool,toggleSchoolStatus" />

</div>
