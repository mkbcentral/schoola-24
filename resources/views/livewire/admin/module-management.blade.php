<div>
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-puzzle text-primary"></i>
                    Gestion des Modules
                </h3>
                <p class="text-muted mb-0">Créer et gérer les modules de l'application</p>
            </div>
            <a href="{{ route('admin.modules.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-lg me-2"></i> Créer un module
            </a>
        </div>

        {{-- Messages Flash --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                 style="border-radius: 12px; border: none;">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div class="grow">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                 style="border-radius: 12px; border: none;">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div class="grow">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Barre de recherche --}}
        <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div class="card-body p-4">
                <div class="position-relative">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-2"></i>Rechercher un module
                    </label>
                    <input type="text" wire:model.live="search" class="form-control form-control-lg"
                           placeholder="Tapez le nom ou le code du module..."
                           style="border: 2px solid var(--input-border, #e1e4e8); border-radius: 8px; padding: 0.75rem 1rem;">
                    <div wire:loading wire:target="search" class="mt-2">
                        <small class="text-muted">
                            <span class="spinner-border spinner-border-sm me-2"></span>Recherche en cours...
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des modules --}}
        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 fw-semibold">Icône</th>
                                <th class="px-4 py-3 fw-semibold">Nom</th>
                                <th class="px-4 py-3 fw-semibold">Code</th>
                                <th class="px-4 py-3 fw-semibold">Fonctionnalités</th>
                                <th class="px-4 py-3 fw-semibold">Écoles</th>
                                <th class="px-4 py-3 fw-semibold">Statut</th>
                                <th class="px-4 py-3 fw-semibold text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modules as $module)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="px-4 py-3">
                                        <i class="{{ $module->icon }} text-primary" style="font-size: 2rem;"></i>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold">{{ $module->name }}</div>
                                        <small class="text-muted">{{ Str::limit($module->description, 40) }}</small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge text-bg-secondary">{{ $module->code }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge text-bg-info">
                                            <i class="bi bi-list-stars me-1"></i>
                                            {{ $module->features_count }} fonctionnalité(s)
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge text-bg-secondary">
                                            <i class="bi bi-building me-1"></i>
                                            {{ $module->schools_count }} école(s)
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($module->is_active)
                                            <span class="badge text-bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Actif
                                            </span>
                                        @else
                                            <span class="badge text-bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group" role="group">
                                            <button wire:click="edit({{ $module->id }})"
                                                    class="btn btn-sm btn-outline-primary" title="Modifier">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

                                            <button wire:click="toggleActive({{ $module->id }})"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="{{ $module->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="bi bi-{{ $module->is_active ? 'toggle-on' : 'toggle-off' }} fs-5"></i>
                                            </button>

                                            <button wire:click="delete({{ $module->id }})"
                                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce module ?"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Supprimer">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <p class="mb-2">Aucun module trouvé</p>
                                            <button wire:click="create" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-lg me-1"></i>Créer le premier module
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($modules->hasPages())
                    <div class="p-4 border-top">
                        {{ $modules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
