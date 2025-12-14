<div>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-0">
                    <i class="bi bi-cash-stack me-2"></i>
                    Gestion des frais
                </h2>
                <p class="text-muted">Gérez les catégories et les frais scolaires</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        @if ($statistics)
            <div class="row mb-4">
                <x-v2.mini-stat-card title="Catégories d'inscription" :value="$statistics['categoryRegistrationFee']['total'] ?? 0" icon="bi-folder" color="primary" />
                <x-v2.mini-stat-card title="Catégories de frais" :value="$statistics['categoryFee']['total'] ?? 0" icon="bi-folder-fill"
                    color="success" />
                <x-v2.mini-stat-card title="Frais scolaires" :value="$statistics['scolarFee']['total'] ?? 0" icon="bi-currency-exchange"
                    color="info" />
                <x-v2.mini-stat-card title="Frais d'inscription" :value="$statistics['registrationFee']['total'] ?? 0" icon="bi-person-check"
                    color="warning" />
            </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="feeManagementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'category-registration-fee' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'category-registration-fee')" wire:loading.attr="disabled"
                    type="button">
                    <i class="bi bi-folder me-1"></i>
                    Catégories d'inscription
                    <span wire:loading wire:target="activeTab" class="spinner-border spinner-border-sm ms-1"></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'category-fee' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'category-fee')" type="button">
                    <i class="bi bi-folder-fill me-1"></i>
                    Catégories de frais
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'scolar-fee' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'scolar-fee')" type="button">
                    <i class="bi bi-currency-exchange me-1"></i>
                    Frais scolaires
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'registration-fee' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'registration-fee')" type="button">
                    <i class="bi bi-person-check me-1"></i>
                    Frais d'inscription
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Category Registration Fee Tab -->
            <div class="tab-pane fade {{ $activeTab === 'category-registration-fee' ? 'show active' : '' }}"
                wire:key="tab-category-registration-fee">
                @include('livewire.application.v2.fee.partials.category-registration-fee-list')
            </div>

            <!-- Category Fee Tab -->
            <div class="tab-pane fade {{ $activeTab === 'category-fee' ? 'show active' : '' }}"
                wire:key="tab-category-fee">
                @include('livewire.application.v2.fee.partials.category-fee-list')
            </div>

            <!-- Scolar Fee Tab -->
            <div class="tab-pane fade {{ $activeTab === 'scolar-fee' ? 'show active' : '' }}" wire:key="tab-scolar-fee">
                @include('livewire.application.v2.fee.partials.scolar-fee-list')
            </div>

            <!-- Registration Fee Tab -->
            <div class="tab-pane fade {{ $activeTab === 'registration-fee' ? 'show active' : '' }}"
                wire:key="tab-registration-fee">
                @include('livewire.application.v2.fee.partials.registration-fee-list')
            </div>
        </div>
    </div>

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />

    <!-- Include all form offcanvas components with lazy loading -->
    @livewire('application.v2.fee.form.category-registration-fee-form-offcanvas', [], key('category-registration-fee-form'))
    @livewire('application.v2.fee.form.category-fee-form-offcanvas', [], key('category-fee-form'))
    @livewire('application.v2.fee.form.scolar-fee-form-offcanvas', [], key('scolar-fee-form'))
    @livewire('application.v2.fee.form.registration-fee-form-offcanvas', [], key('registration-fee-form'))
</div>
