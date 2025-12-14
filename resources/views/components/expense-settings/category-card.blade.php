@props(['category'])

<div class="col-md-6 col-lg-4" wire:key="category-{{ $category->id }}">
    <div class="card h-100 shadow-sm hover-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="grow">
                    <h5 class="card-title mb-1">
                        <i class="bi bi-tag text-primary me-2"></i>
                        {{ $category->name }}
                    </h5>
                    <small class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        Créée le {{ $category->created_at->format('d/m/Y') }}
                    </small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm " type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="offcanvas"
                                data-bs-target="#categoryFormOffcanvas"
                                wire:click="openEditCategoryModal({{ $category->id }})">
                                <i class="bi bi-pencil text-primary me-2"></i>
                                Modifier
                            </button>
                        </li>
                        @php
                            $totalExpenses = $category->expenseFee->count() + $category->otherExpenses->count();
                        @endphp
                        @if ($totalExpenses === 0)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button class="dropdown-item text-danger"
                                    wire:click="confirmDeleteCategory({{ $category->id }})">
                                    <i class="bi bi-trash me-2"></i>
                                    Supprimer
                                </button>
                            </li>
                        @else
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <span class="dropdown-item text-muted disabled" style="cursor: not-allowed;">
                                    <i class="bi bi-lock me-2"></i>
                                    {{ $totalExpenses }} dépense(s)
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row g-2 mt-3">
                <div class="col-6">
                    <div class="p-2 card rounded text-center">
                        <small class="text-muted d-block">Dép. sur Frais</small>
                        <strong class="text-primary">{{ $category->expenseFee->count() }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 card rounded text-center">
                        <small class="text-muted d-block">Autres Dép.</small>
                        <strong class="text-info">{{ $category->otherExpenses->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
