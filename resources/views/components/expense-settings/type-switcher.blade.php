@props([
    'activeTab' => 'categories',
])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <!-- Tab Buttons -->
            <div class="btn-group" role="group">
                <button type="button"
                    class="btn {{ $activeTab === 'categories' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="switchTab('categories')" wire:loading.attr="disabled">
                    <i class="bi bi-tags me-2"></i>
                    Catégories de Dépenses
                </button>
                <button type="button"
                    class="btn {{ $activeTab === 'sources' ? 'btn-primary' : 'btn-outline-primary' }}"
                    wire:click="switchTab('sources')" wire:loading.attr="disabled">
                    <i class="bi bi-folder2-open me-2"></i>
                    Sources Autres Dépenses
                </button>
            </div>
            <!-- Search Input -->
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Rechercher..." wire:model.live="search">
            </div>
            <!-- Search and Add -->
            <div class="d-flex gap-2 align-items-center">


                <!-- Add Button -->
                @if ($activeTab === 'categories')
                    <button type="button" class="btn btn-success" data-bs-toggle="offcanvas"
                        data-bs-target="#categoryFormOffcanvas" wire:click="openCreateCategoryModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nouvelle Catégorie
                    </button>
                @else
                    <button type="button" class="btn btn-success" data-bs-toggle="offcanvas"
                        data-bs-target="#sourceFormOffcanvas" wire:click="openCreateSourceModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nouvelle Source
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
