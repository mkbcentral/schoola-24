<div>
    <x-navigation.bread-crumb icon='bi bi-box-seam' label="Stock">
        <x-navigation.bread-crumb-item label='Catalogue' />
        <x-navigation.bread-crumb-item label='Dashboard Stock' isLinked=true link="stock.dashboard" />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête avec statistiques -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold">
                                    <i class="bi bi-box-seam me-2"></i>Gestion du Stock
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('stock.dashboard') }}" class="btn btn-light btn-sm mb-2">
                                    <i class="bi bi-graph-up-arrow me-1"></i>
                                    Dashboard
                                </a>
                                <h2 class="mb-0 fw-bold">{{ $articles->total() }}</h2>
                                <small class="opacity-75">Articles au catalogue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes de stock -->
        @if ($lowStockArticles->isNotEmpty())
            <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">
                            <strong>{{ $lowStockArticles->count() }}</strong> article(s) en alerte de stock
                        </h5>
                        <p class="mb-0 small">Les articles suivants ont atteint ou dépassé leur seuil minimum</p>
                    </div>
                </div>
                <div class="row g-2">
                    @foreach ($lowStockArticles as $article)
                        <div class="col-md-6">
                            <div
                                class="card border-0 {{ $article->is_critical_stock ? 'bg-danger' : 'bg-warning' }} bg-opacity-10">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="grow">
                                            <strong class="d-block">{{ $article->name }}</strong>
                                            <small class="text-muted">
                                                @if ($article->reference)
                                                    Réf: {{ $article->reference }} •
                                                @endif
                                                Min: {{ $article->stock_min }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            @if ($article->is_critical_stock)
                                                <span class="badge text-bg-danger fs-6">
                                                    <i class="bi bi-x-circle me-1"></i>{{ $article->stock }}
                                                </span>
                                            @else
                                                <span class="badge text-bg-warning fs-6">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>{{ $article->stock }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Formulaire d'ajout/modification -->
        @if (auth()->user()->role->name === \App\Enums\RoleType::SCHOOL_GUARD)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-{{ $editMode ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                            {{ $editMode ? 'Modifier un article' : 'Ajouter un nouvel article' }}
                        </h5>
                        @if ($editMode)
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                                <i class="bi bi-x-circle me-1"></i>Annuler
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body bg-light">
                    <form wire:submit.prevent="{{ $editMode ? 'updateArticle' : 'createArticle' }}">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1 text-primary"></i>Nom de l'article *
                                </label>
                                <x-form.input type="text" wire:model.blur="form.name" :error="'form.name'"
                                    placeholder="Ex: Cahier 100 pages" />
                                <x-errors.validation-error value="form.name" />
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-upc-scan me-1 text-primary"></i>Référence
                                </label>
                                <x-form.input type="text" wire:model.blur="form.reference" :error="'form.reference'"
                                    placeholder="Ex: CAH-100" />
                                <x-errors.validation-error value="form.reference" />
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-rulers me-1 text-primary"></i>Unité
                                </label>
                                <input type="text" class="form-control @error('form.unit') is-invalid @enderror"
                                    wire:model.blur="form.unit" list="unitList" placeholder="Choisir ou saisir">
                                <datalist id="unitList">
                                    <option value="Pièce">
                                    <option value="Boîte">
                                    <option value="Paquet">
                                    <option value="Kg">
                                    <option value="Gramme">
                                    <option value="Litre">
                                    <option value="Mètre">
                                    <option value="Carton">
                                    <option value="Lot">
                                    <option value="Unité">
                                </datalist>
                                <x-errors.validation-error value="form.unit" />
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-card-text me-1 text-primary"></i>Description
                                </label>
                                <x-form.input type="text" wire:model.blur="form.description" :error="'form.description'"
                                    placeholder="Description courte" />
                                <x-errors.validation-error value="form.description" />
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag-fill me-1 text-info"></i>Catégorie
                                </label>
                                <select class="form-select @error('form.category_id') is-invalid @enderror"
                                    wire:model.blur="form.category_id">
                                    <option value="">Aucune catégorie</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-errors.validation-error value="form.category_id" />
                            </div>
                            <div class="col-md-6 col-lg-1">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-exclamation-triangle me-1 text-warning"></i>Min.
                                </label>
                                <x-form.input type="number" wire:model.blur="form.stock_min" :error="'form.stock_min'"
                                    placeholder="0" min="0" />
                                <x-errors.validation-error value="form.stock_min" />
                            </div>
                            <div class="col-md-12 col-lg-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-{{ $editMode ? 'check-circle' : 'plus-circle' }} me-2"></i>
                                    {{ $editMode ? 'Modifier' : 'Ajouter' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des articles -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="row align-items-center g-2">
                        <div class="col-md-4">
                            <h5 class="mb-0 text-primary">
                                <i class="bi bi-list-ul me-2"></i>Catalogue des articles
                            </h5>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" placeholder="Rechercher..."
                                    wire:model.live.debounce.300ms="search">
                                @if ($search)
                                    <button class="btn btn-outline-secondary" type="button"
                                        wire:click="$set('search', '')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="selectedCategory">
                                <option value="">Toutes les catégories</option>
                                <option value="none">Sans catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-success btn-sm" wire:click="exportArticles">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel
                            </button>
                            <a href="{{ route('stock.categories') }}" class="btn btn-info btn-sm text-white">
                                <i class="bi bi-tag-fill me-1"></i>
                                Catégories
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">

                    <!-- Tableau des articles -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Nom</th>
                                    <th>Référence</th>
                                    <th>Catégorie</th>
                                    <th>Unité</th>
                                    <th>Description</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($articles as $article)
                                    <tr>
                                        <td class="fw-bold">{{ $article->name }}</td>
                                        <td><span class="text-muted">{{ $article->reference ?? '-' }}</span></td>
                                        <td>
                                            @if ($article->category)
                                                <span class="badge"
                                                    style="background-color: {{ $article->category->color }}; color: {{ $article->category->text_color ?? '#ffffff' }};"></span>
                                                {{ $article->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td><span class="badge text-bg-secondary">{{ $article->unit ?? '-' }}</span>
                                        </td>
                                        <td>{{ Str::limit($article->description, 50) ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($article->is_critical_stock)
                                                <span class="badge text-bg-danger fs-6"
                                                    title="Stock critique (0 ou négatif)">
                                                    <i class="bi bi-x-circle-fill me-1"></i>{{ $article->stock }}
                                                </span>
                                            @elseif($article->is_low_stock)
                                                <span class="badge text-bg-warning fs-6"
                                                    title="Stock faible (≤ {{ $article->stock_min }})">
                                                    <i
                                                        class="bi bi-exclamation-triangle-fill me-1"></i>{{ $article->stock }}
                                                </span>
                                            @elseif($article->stock > 0)
                                                <span class="badge text-bg-success fs-6">
                                                    <i class="bi bi-archive-fill me-1"></i>{{ $article->stock }}
                                                </span>
                                            @else
                                                <span class="badge text-bg-secondary fs-6">
                                                    <i class="bi bi-archive me-1"></i>{{ $article->stock }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm" title="Voir mouvements"
                                                    wire:click="showStockMovements({{ $article->id }})">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </button>
                                                @if (auth()->user()->role->name === \App\Enums\RoleType::SCHOOL_GUARD)
                                                    <button class="btn btn-outline-warning btn-sm" title="Éditer"
                                                        wire:click="editArticle({{ $article->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm" title="Supprimer"
                                                        wire:click="deleteArticle({{ $article->id }})"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 mb-2"></i>
                                            Aucun article trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top">
                    {{ $articles->links() }}
                </div>
            </div>
    </x-content.main-content-page>
</div>
