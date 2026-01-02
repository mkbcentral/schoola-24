<div>
    <x-navigation.bread-crumb icon='bi bi-credit-card-2-front' label="Gestion des Paiements V3">
        <x-navigation.bread-crumb-item label='Paiements' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="row g-4">
            <!-- Colonne gauche : Recherche et formulaire (sticky) -->
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 1rem;">
                    <!-- Card de recherche d'élève -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="mb-0">
                                <i class="bi bi-search me-2"></i>Recherche d'élève
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="position-relative">
                                <label for="student-search" class="form-label fw-semibold">
                                    Nom de l'élève
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-person-search"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        id="student-search"
                                        wire:model.live.debounce.300ms="search"
                                        class="form-control border-start-0 ps-0"
                                        placeholder="Tapez au moins 2 caractères..."
                                        autocomplete="off">
                                    @if(strlen($search) > 0)
                                        <button 
                                            type="button" 
                                            wire:click="$set('search', '')"
                                            class="btn btn-outline-secondary">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Indicateur de chargement -->
                                <div wire:loading wire:target="search" class="mt-2">
                                    <div class="d-flex align-items-center text-primary">
                                        <div class="spinner-border spinner-border-sm me-2" role="status">
                                            <span class="visually-hidden">Recherche...</span>
                                        </div>
                                        <small>Recherche en cours...</small>
                                    </div>
                                </div>

                                <!-- Dropdown des résultats -->
                                @if($showDropdown && count($searchResults) > 0)
                                    <div class="dropdown-results position-absolute w-100 mt-2 shadow-lg" 
                                         style="z-index: 1050; max-height: 400px; overflow-y: auto;">
                                        <div class="list-group">
                                            @foreach($searchResults as $result)
                                                <button 
                                                    type="button"
                                                    wire:click="selectStudent({{ $result['id'] }}, '{{ addslashes($result['student_name']) }}')"
                                                    class="list-group-item list-group-item-action">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="fw-bold">
                                                                <i class="bi bi-person-circle text-primary me-2"></i>
                                                                {{ $result['student_name'] }}
                                                            </div>
                                                            <small class="text-muted">
                                                                <span class="badge bg-info me-1">{{ $result['code'] }}</span>
                                                                {{ $result['class_room'] }} - {{ $result['option'] }}
                                                            </small>
                                                        </div>
                                                        <i class="bi bi-arrow-right-circle text-primary"></i>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($showDropdown && strlen($search) >= 2 && count($searchResults) === 0)
                                    <div class="alert alert-info mt-2 mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Aucun élève trouvé pour "{{ $search }}"
                                    </div>
                                @endif

                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-lightbulb me-1"></i>Astuce : Tapez le nom complet ou partiel
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de l'élève sélectionné -->
                    @if($selectedRegistrationId && !empty($studentInfo))
                        <div class="card shadow-sm border-0 mb-4 bg-gradient-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="bi bi-person-badge-fill me-2"></i>
                                            {{ $studentInfo['name'] ?? '' }}
                                        </h5>
                                        <p class="mb-1 small">
                                            <i class="bi bi-bookmark-fill me-1"></i>
                                            Code: {{ $studentInfo['code'] ?? '' }}
                                        </p>
                                        <p class="mb-0 small">
                                            <i class="bi bi-building me-1"></i>
                                            {{ $studentInfo['class_room'] ?? '' }} - {{ $studentInfo['option'] ?? '' }}
                                        </p>
                                    </div>
                                    <button 
                                        type="button" 
                                        wire:click="resetStudent"
                                        class="btn btn-sm btn-light rounded-circle"
                                        style="width: 32px; height: 32px; padding: 0;"
                                        title="Désélectionner">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Formulaire de paiement (Composant Livewire) -->
                    @if($showForm)
                        @livewire('application.v3.payment.payment-form', key('payment-form-' . $selectedRegistrationId))
                    @endif
                </div>
            </div>

            <!-- Colonne droite : Liste des paiements (Composant Livewire) -->
            <div class="col-lg-7">
                @livewire('application.v3.payment.payment-list', key('payment-list'))
            </div>
        </div>
    </x-content.main-content-page>
</div>

@push('styles')
<style>
    /* Gradient backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    /* Dropdown results */
    .dropdown-results {
        background: white;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .dropdown-results .list-group-item {
        border: none;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .dropdown-results .list-group-item:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }

    .dropdown-results .list-group-item:last-child {
        border-bottom: none;
    }

    /* Form switches */
    .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    /* Table hover effect */
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Sticky positioning */
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky;
            z-index: 1020;
        }
    }

    /* Animation for cards */
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Fermer le dropdown au clic en dehors
    document.addEventListener('click', function(event) {
        const searchInput = document.getElementById('student-search');
        const dropdown = document.querySelector('.dropdown-results');
        
        if (dropdown && searchInput) {
            if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
                @this.call('closeDropdown');
            }
        }
    });

    // Notifications
    window.addEventListener('notification', event => {
        const data = event.detail[0] || event.detail;
        const type = data.type || 'info';
        const message = data.message || 'Notification';
        
        // Vous pouvez utiliser votre système de notification ici
        console.log(`[${type.toUpperCase()}] ${message}`);
    });
</script>
@endpush
