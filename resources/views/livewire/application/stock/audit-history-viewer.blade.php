<div>
    <x-navigation.bread-crumb icon='bi bi-clock-history' label="Stock">
        <x-navigation.bread-crumb-item label='Historique Audit' />
        <x-navigation.bread-crumb-item label='Catalogue' isLinked=true link="stock.main" />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="stock.dashboard" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-0">
                                    <i class="bi bi-clock-history text-info me-2"></i>
                                    Historique des Modifications
                                </h4>
                                <p class="text-muted mb-0 small">
                                    @if ($article)
                                        Audit pour: <strong>{{ $article->name }}</strong>
                                        <button class="btn btn-sm btn-outline-secondary ms-2"
                                            wire:click="viewArticle(null)">
                                            <i class="bi bi-x"></i> Voir tout
                                        </button>
                                    @else
                                        Traçabilité complète des actions sur les articles
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-info fs-6">
                                    <i class="bi bi-file-text me-1"></i>
                                    {{ $stats['total'] }} enregistrements
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Articles -->
        <div class="mb-3">
            <h6 class="text-muted mb-3"><i class="bi bi-box-seam me-2"></i>Actions sur Articles</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                    <i class="bi bi-plus-circle fs-5 text-success"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Créations</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['created'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                    <i class="bi bi-pencil fs-5 text-primary"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Modifications</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['updated'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                    <i class="bi bi-arrow-repeat fs-5 text-warning"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Ajustements</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['stock_adjusted'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                                    <i class="bi bi-trash fs-5 text-danger"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Suppressions</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['deleted'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Mouvements -->
        <div class="mb-4">
            <h6 class="text-muted mb-3"><i class="bi bi-arrow-left-right me-2"></i>Actions sur Mouvements</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                    <i class="bi bi-arrow-down-circle fs-5 text-success"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Créations</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['movement_created'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-info bg-opacity-10 p-2">
                                    <i class="bi bi-pencil-square fs-5 text-info"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Modifications</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['movement_updated'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-dark bg-opacity-10 p-2">
                                    <i class="bi bi-lock-fill fs-5 text-dark"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Clôtures</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['movement_closed'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                                    <i class="bi bi-x-circle fs-5 text-danger"></i>
                                </div>
                                <div class="ms-2">
                                    <h6 class="text-muted mb-0 small">Suppressions</h6>
                                    <h5 class="mb-0 fw-bold">{{ $stats['movement_deleted'] }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    @if (!$article)
                        <div class="col-md-3">
                            <label class="form-label small">Article</label>
                            <select class="form-select" wire:model.live="articleId">
                                <option value="">Tous les articles</option>
                                @foreach ($articles as $art)
                                    <option value="{{ $art->id }}">{{ $art->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <label class="form-label small">Action</label>
                        <select class="form-select" wire:model.live="actionFilter">
                            <option value="">Toutes</option>
                            <optgroup label="Articles">
                                <option value="created">Création Article</option>
                                <option value="updated">Modification Article</option>
                                <option value="stock_adjusted">Ajustement Stock</option>
                                <option value="deleted">Suppression Article</option>
                            </optgroup>
                            <optgroup label="Mouvements">
                                <option value="movement_created">Mouvement Créé</option>
                                <option value="movement_updated">Mouvement Modifié</option>
                                <option value="movement_closed">Mouvement Clôturé</option>
                                <option value="movement_deleted">Mouvement Supprimé</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Utilisateur</label>
                        <select class="form-select" wire:model.live="userFilter">
                            <option value="">Tous</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date début</label>
                        <input type="date" class="form-control" wire:model.live="dateFrom">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date fin</label>
                        <input type="date" class="form-control" wire:model.live="dateTo">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters" title="Réinitialiser">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline des modifications -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @forelse($audits as $audit)
                    <div class="row mb-4 position-relative">
                        <!-- Timeline indicator -->
                        <div class="col-auto text-center position-relative">
                            <div class="rounded-circle bg-{{ $audit->action_badge }} bg-opacity-10 p-3 border-3 border-{{ $audit->action_badge }}"
                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi {{ $audit->action_icon }} fs-4 text-{{ $audit->action_badge }}"></i>
                            </div>
                            @if (!$loop->last)
                                <div class="position-absolute top-100 start-50 translate-middle-x bg-secondary opacity-25"
                                    style="width: 2px; height: 60px;"></div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="col">
                            <div class="card border border-{{ $audit->action_badge }} border-opacity-25 shadow-sm">
                                <div
                                    class="card-header bg-{{ $audit->action_badge }} bg-opacity-10 border-bottom border-{{ $audit->action_badge }} border-opacity-25">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-0">
                                                <span
                                                    class="badge bg-{{ $audit->action_badge }} me-2">{{ $audit->action_label }}</span>
                                                @if ($audit->article)
                                                    <strong>{{ $audit->article->name }}</strong>
                                                    @if ($audit->article->reference)
                                                        <small
                                                            class="text-muted">({{ $audit->article->reference }})</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Article supprimé</span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $audit->user->name ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            @if ($audit->changes && count($audit->changes) > 0)
                                                <table class="table table-sm table-borderless mb-0">
                                                    <thead>
                                                        <tr class="text-muted small">
                                                            <th style="width: 150px;">Champ</th>
                                                            <th>Avant</th>
                                                            <th style="width: 30px;"></th>
                                                            <th>Après</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($audit->changes as $field => $change)
                                                            <tr>
                                                                <td class="fw-bold">{{ $change['label'] }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge text-bg-light text-dark border">{{ $change['old'] ?? '—' }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <i class="bi bi-arrow-right text-primary"></i>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge text-bg-primary">{{ $change['new'] ?? '—' }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @elseif($audit->action == 'created')
                                                <p class="text-muted mb-0">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Article créé avec succès
                                                </p>
                                            @elseif($audit->action == 'deleted')
                                                <p class="text-muted mb-0">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Article supprimé définitivement
                                                </p>
                                            @endif
                                        </div>
                                        <div class="col-md-3 border-start">
                                            <small class="text-muted d-block">
                                                <i
                                                    class="bi bi-calendar3 me-1"></i>{{ $audit->created_at->format('d/m/Y') }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i
                                                    class="bi bi-clock me-1"></i>{{ $audit->created_at->format('H:i:s') }}
                                            </small>
                                            @if ($audit->ip_address)
                                                <small class="text-muted d-block mt-2">
                                                    <i class="bi bi-globe me-1"></i>{{ $audit->ip_address }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Aucun historique</h5>
                        <p class="text-muted mb-0">Les modifications seront enregistrées automatiquement</p>
                    </div>
                @endforelse
            </div>

            @if ($audits->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $audits->links() }}
                </div>
            @endif
        </div>
    </x-content.main-content-page>
</div>
