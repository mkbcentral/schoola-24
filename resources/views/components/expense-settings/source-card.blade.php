@props(['source'])

<div class="col-md-6 col-lg-4" wire:key="source-{{ $source->id }}">
    <div class="card h-100 shadow-sm hover-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="grow">
                    <h5 class="card-title mb-1">
                        <i class="bi bi-folder2-open text-success me-2"></i>
                        {{ $source->name }}
                    </h5>
                    <small class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        Créée le {{ $source->created_at->format('d/m/Y') }}
                    </small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm " type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="offcanvas"
                                data-bs-target="#sourceFormOffcanvas"
                                wire:click="openEditSourceModal({{ $source->id }})">
                                <i class="bi bi-pencil text-primary me-2"></i>
                                Modifier
                            </button>
                        </li>
                        @php
                            $totalExpenses = $source->otherExpenses->count();
                        @endphp
                        @if ($totalExpenses === 0)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button class="dropdown-item text-danger"
                                    wire:click="confirmDeleteSource({{ $source->id }})">
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
            <div class="mt-3">
                <div class="p-3  rounded text-center">
                    <small class="text-muted d-block">Nombre de dépenses</small>
                    <strong class="text-success fs-4">{{ $source->otherExpenses->count() }}</strong>
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
