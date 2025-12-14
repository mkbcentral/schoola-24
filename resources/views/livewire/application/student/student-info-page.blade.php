<div x-data="{
    showDropdown: @entangle('showDropdown'),
    closeDropdown() {
        showDropdown = false;
        $wire.closeDropdown();
    }
}" @click.outside="closeDropdown()">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="color: var(--bs-dark); font-size: 1.75rem; font-weight: 700; margin: 0;">
            <i class="bi bi-person-badge" style="color: var(--bs-success);"></i> Informations Élève
        </h1>
    </div>

    <!-- Barre de recherche toujours affichée -->
    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="position-relative">
                        <label class="form-label" style="font-weight: 600; color: var(--bs-dark);">
                            <i class="bi bi-search me-2"></i>Rechercher un élève
                        </label>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="form-control form-control-lg" placeholder="Tapez le nom de l'élève..."
                            style="border: 2px solid #e1e4e8; border-radius: 8px; padding: 0.75rem 1rem;"
                            @focus="showDropdown = true" autocomplete="off">

                        <!-- Dropdown autocomplete -->
                        @if (count($searchResults) > 0)
                            <div x-show="showDropdown" x-transition
                                class="position-absolute w-100 bg-white rounded shadow-lg mt-2"
                                style="z-index: var(--z-dropdown); max-height: 400px; overflow-y: auto; border: 1px solid #e1e4e8;">
                                @foreach ($searchResults as $result)
                                    <div wire:click="selectStudent({{ $result['id'] }}, '{{ addslashes($result['student_name']) }}')"
                                        class="p-3 border-bottom cursor-pointer"
                                        style="cursor: pointer; transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='var(--bs-light)'"
                                        onmouseout="this.style.backgroundColor='white'">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div style="font-weight: 600; color: #1a1f36; font-size: 1rem;">
                                                    <i class="bi bi-person-circle me-2" style="color: #059669;"></i>
                                                    {{ $result['student_name'] }}
                                                </div>
                                                <div style="color: #6b7280; font-size: 0.85rem;">
                                                    <span class="badge bg-primary me-2">{{ $result['code'] }}</span>
                                                    {{ $result['class_room'] }} - {{ $result['option'] }}
                                                </div>
                                            </div>
                                            <i class="bi bi-arrow-right-circle"
                                                style="color: #059669; font-size: 1.2rem;"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif (strlen($search) >= 2 && count($searchResults) === 0)
                            <div x-show="showDropdown" x-transition
                                class="position-absolute w-100 bg-white rounded shadow-lg mt-2 p-3"
                                style="z-index: var(--z-dropdown); border: 1px solid #e1e4e8;">
                                <div class="text-center text-muted">
                                    <i class="bi bi-search me-2"></i>Aucun élève trouvé
                                </div>
                            </div>
                        @endif

                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle me-1"></i>Tapez au moins 2 caractères pour rechercher
                        </small>
                    </div>

                    <!-- Indicateur de chargement -->
                    <div wire:loading wire:target="updatedSearch" class="mt-3">
                        <div class="alert alert-info d-flex align-items-center mb-0" role="alert"
                            style="border-radius: 8px; border: none; background-color: #e0f2fe; color: #0369a1;">
                            <div class="spinner-border spinner-border-sm me-3" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <div>
                                <strong>Recherche en cours...</strong>
                                <div class="small">Veuillez patienter</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($studentInfo)
                    <div class="col-lg-4 text-end">
                        <button type="button" wire:click="resetStudentInfo"
                            class="btn btn-outline-secondary btn-lg mb-2">
                            <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Affichage des informations de l'élève -->
    @if ($studentInfo)
        <div class="row">
            <!-- Section gauche: Formulaire de paiement -->
            <div class="col-lg-4 mb-4">
                <div class="card sticky-top"
                    style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); top: 20px;">
                    <div class="card-header bg-light border-bottom"
                        style="border-radius: 12px 12px 0 0; padding: 1rem;">
                        <h6 class="mb-0" style="color: var(--bs-dark);"><i
                                class="bi bi-cash-coin me-2 text-success"></i>Nouveau Paiement</h6>
                    </div>
                    <div class="card-body p-3">
                        <!-- Formulaire de paiement -->
                        <form>
                            <div class="mb-3">
                                <label class="form-label small" style="font-weight: 600;">Catégorie de frais</label>
                                <select wire:model.live="selectedCategoryId" class="form-select form-select-sm">
                                    @foreach ($availableCategories as $category)
                                        <option value="{{ $category['id'] }}">
                                            {{ $category['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small" style="font-weight: 600;">Mois</label>
                                <select class="form-select form-select-sm">
                                    <option>Sélectionner un mois</option>
                                    <option>Septembre</option>
                                    <option>Octobre</option>
                                    <option>Novembre</option>
                                    <option>Décembre</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small" style="font-weight: 600;">Montant</label>
                                <input type="number" class="form-control form-control-sm" placeholder="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small" style="font-weight: 600;">Devise</label>
                                <select class="form-select form-select-sm">
                                    <option>CDF</option>
                                    <option>USD</option>
                                </select>
                            </div>

                            <button type="button" class="btn btn-success w-100 btn-sm">
                                <i class="bi bi-check-circle me-2"></i>Enregistrer le paiement
                            </button>
                        </form>

                        <!-- Stats rapides -->
                        <div class="mt-3 pt-3 border-top">
                            <div class="small text-muted mb-2" style="font-weight: 600;">Résumé</div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small">Mois payés</span>
                                <span class="badge bg-success">{{ $studentInfo['summary']['total_months_paid'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small">Mois impayés</span>
                                <span
                                    class="badge bg-danger">{{ $studentInfo['summary']['total_months_unpaid'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small">Total paiements</span>
                                <span
                                    class="badge bg-primary">{{ $studentInfo['summary']['total_payments_made'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section droite: Informations détaillées -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Identité et Inscription combinées -->
                    <div class="col-12 mb-4">
                        <div class="card"
                            style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="card-header bg-light border-bottom"
                                style="border-radius: 12px 12px 0 0; padding: 1rem;">
                                <h6 class="mb-0" style="color: var(--bs-dark);"><i
                                        class="bi bi-person-vcard me-2 text-primary"></i>Identité et Inscription</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <!-- Section Identité -->
                                    <div class="col-lg-6">
                                        <h6 class="text-primary mb-3" style="font-size: 0.9rem; font-weight: 600;">
                                            <i class="bi bi-person me-2"></i>IDENTITÉ DE L'ÉLÈVE
                                        </h6>
                                        <div class="mb-3">
                                            <strong style="color: var(--bs-secondary);">Nom complet:</strong>
                                            <div style="font-size: 1.1rem; font-weight: 600; color: var(--bs-dark);">
                                                {{ $studentInfo['student']['name'] }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <strong style="color: var(--bs-secondary);">Date de naissance:</strong>
                                                <div>{{ $studentInfo['student']['date_of_birth'] ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <strong style="color: var(--bs-secondary);">Âge:</strong>
                                                <div>{{ $studentInfo['student']['age'] ?? 'N/A' }} ans</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <strong style="color: var(--bs-secondary);">Lieu de naissance:</strong>
                                            <div>{{ $studentInfo['student']['place_of_birth'] ?? 'N/A' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <strong style="color: var(--bs-secondary);">Genre:</strong>
                                            <div>{{ $studentInfo['student']['gender'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <!-- Séparateur vertical -->
                                    <div class="col-lg-6 border-start ps-4">
                                        <h6 class="text-primary mb-3" style="font-size: 0.9rem; font-weight: 600;">
                                            <i class="bi bi-building me-2"></i>INSCRIPTION
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <strong style="color: #6b7280;">Code:</strong>
                                                <div><span
                                                        class="badge bg-primary fs-6">{{ $studentInfo['registration']['code'] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <strong style="color: #6b7280;">Classe:</strong>
                                                <div>{{ $studentInfo['registration']['class_room'] }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <strong style="color: #6b7280;">Option:</strong>
                                                <div>{{ $studentInfo['registration']['option'] ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <strong style="color: #6b7280;">Année scolaire:</strong>
                                                <div>{{ $studentInfo['registration']['school_year'] }}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <strong style="color: #6b7280;">Date d'inscription:</strong>
                                                <div>{{ $studentInfo['registration']['registration_date'] }}</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <strong style="color: #6b7280;">Ancien élève:</strong>
                                                <div>
                                                    @if ($studentInfo['registration']['is_old'])
                                                        <span class="badge bg-success">Oui</span>
                                                    @else
                                                        <span class="badge bg-secondary">Non</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <strong style="color: #6b7280;">Sous dérogation:</strong>
                                                <div>
                                                    @if ($studentInfo['registration']['is_under_derogation'])
                                                        <span class="badge bg-warning">Oui</span>
                                                    @else
                                                        <span class="badge bg-success">Non</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paiements mensuels -->
                    <div class="col-xl-6 mb-4">
                        <div class="card h-100"
                            style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="card-header bg-light border-bottom"
                                style="border-radius: 12px 12px 0 0; padding: 1rem;">
                                <h6 class="mb-0" style="color: #1a1f36;">
                                    <i class="bi bi-calendar-check me-2 text-success"></i>Paiements Mensuels
                                    <small
                                        class="text-muted">({{ $studentInfo['monthly_payments']['category_name'] }})</small>
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="text-center p-3 rounded"
                                            style="background-color: #d1fae5; border: 2px solid #10b981;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #059669;">
                                                {{ $studentInfo['monthly_payments']['paid_months_count'] }}
                                            </div>
                                            <div style="color: #047857; font-weight: 600;">Mois payés</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 rounded"
                                            style="background-color: #fee2e2; border: 2px solid #ef4444;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">
                                                {{ $studentInfo['monthly_payments']['unpaid_months_count'] }}
                                            </div>
                                            <div style="color: #b91c1c; font-weight: 600;">Mois impayés</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Liste des mois payés -->
                                @if (count($studentInfo['monthly_payments']['paid_months']) > 0)
                                    <div class="mb-3">
                                        <h6 style="color: #059669; font-weight: 600;">
                                            <i class="bi bi-check-circle me-2"></i>Mois payés
                                        </h6>
                                        <div style="max-height: 250px; overflow-y: auto;">
                                            @foreach ($studentInfo['monthly_payments']['paid_months'] as $month)
                                                <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded"
                                                    style="background-color: #f0fdf4; border-left: 3px solid #10b981;">
                                                    <div>
                                                        <strong>{{ $month['month'] }}</strong>
                                                        <small
                                                            class="text-muted d-block">{{ $month['paid_at'] }}</small>
                                                    </div>
                                                    <span class="badge bg-success">
                                                        {{ number_format($month['amount'], 0, ',', ' ') }}
                                                        {{ $month['currency'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Liste des mois impayés -->
                                @if (count($studentInfo['monthly_payments']['unpaid_months']) > 0)
                                    <div>
                                        <h6 style="color: #dc2626; font-weight: 600;">
                                            <i class="bi bi-x-circle me-2"></i>Mois impayés
                                        </h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($studentInfo['monthly_payments']['unpaid_months'] as $month)
                                                <span class="badge bg-danger">{{ $month['month'] }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Autres frais -->
                    <div class="col-xl-6 mb-4">
                        <div class="card h-100"
                            style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="card-header bg-light border-bottom"
                                style="border-radius: 12px 12px 0 0; padding: 1rem;">
                                <h6 class="mb-0" style="color: #1a1f36;"><i
                                        class="bi bi-cash-stack me-2 text-warning"></i>Autres Frais</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="text-center p-3 rounded"
                                            style="background-color: #dbeafe; border: 2px solid #3b82f6;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #2563eb;">
                                                {{ $studentInfo['fees_payments']['paid_fees_count'] }}
                                            </div>
                                            <div style="color: #1d4ed8; font-weight: 600;">Frais payés</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 rounded"
                                            style="background-color: #fef3c7; border: 2px solid #f59e0b;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #d97706;">
                                                {{ $studentInfo['fees_payments']['unpaid_fees_count'] }}
                                            </div>
                                            <div style="color: #b45309; font-weight: 600;">Frais impayés</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Frais payés -->
                                @if (count($studentInfo['fees_payments']['paid_fees']) > 0)
                                    <div class="mb-3">
                                        <h6 style="color: #2563eb; font-weight: 600;">
                                            <i class="bi bi-check-circle me-2"></i>Frais payés
                                        </h6>
                                        <div style="max-height: 200px; overflow-y: auto;">
                                            @foreach ($studentInfo['fees_payments']['paid_fees'] as $fee)
                                                <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded"
                                                    style="background-color: #eff6ff; border-left: 3px solid #3b82f6;">
                                                    <div>
                                                        <strong>{{ $fee['category_name'] }}</strong>
                                                        <small
                                                            class="text-muted d-block">{{ $fee['paid_at'] }}</small>
                                                    </div>
                                                    <span class="badge bg-primary">
                                                        {{ number_format($fee['amount'], 0, ',', ' ') }}
                                                        {{ $fee['currency'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Frais impayés -->
                                @if (count($studentInfo['fees_payments']['unpaid_fees']) > 0)
                                    <div>
                                        <h6 style="color: #d97706; font-weight: 600;">
                                            <i class="bi bi-exclamation-circle me-2"></i>Frais impayés
                                        </h6>
                                        <div style="max-height: 200px; overflow-y: auto;">
                                            @foreach ($studentInfo['fees_payments']['unpaid_fees'] as $fee)
                                                <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded"
                                                    style="background-color: #fffbeb; border-left: 3px solid #f59e0b;">
                                                    <strong>{{ $fee['category_name'] }}</strong>
                                                    <span class="badge bg-warning text-dark">
                                                        {{ number_format($fee['amount'], 0, ',', ' ') }}
                                                        {{ $fee['currency'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Totaux -->
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between">
                                        <strong>Total payé:</strong>
                                        <strong class="text-success">
                                            {{ number_format($studentInfo['fees_payments']['total_paid_amount'], 0, ',', ' ') }}
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total impayé:</strong>
                                        <strong class="text-danger">
                                            {{ number_format($studentInfo['fees_payments']['total_unpaid_amount'], 0, ',', ' ') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé général -->
                    <div class="col-12" style="order: 100;">
                        <div class="card"
                            style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                            <div class="card-header bg-light border-bottom"
                                style="border-radius: 12px 12px 0 0; padding: 1rem;">
                                <h6 class="mb-0" style="color: #1a1f36;"><i
                                        class="bi bi-bar-chart me-2 text-primary"></i>Résumé Général</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded" style="background-color: #f3f4f6;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #6366f1;">
                                                {{ $studentInfo['summary']['total_payments_made'] }}
                                            </div>
                                            <div style="color: #6b7280; font-weight: 600;">Total paiements</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded" style="background-color: #d1fae5;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #059669;">
                                                {{ $studentInfo['summary']['total_months_paid'] }}
                                            </div>
                                            <div style="color: #047857; font-weight: 600;">Mois payés</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded" style="background-color: #dbeafe;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #2563eb;">
                                                {{ $studentInfo['summary']['total_fees_paid'] }}
                                            </div>
                                            <div style="color: #1d4ed8; font-weight: 600;">Frais payés</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 rounded" style="background-color: #fee2e2;">
                                            <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">
                                                {{ $studentInfo['summary']['total_months_unpaid'] + $studentInfo['summary']['total_fees_unpaid'] }}
                                            </div>
                                            <div style="color: #b91c1c; font-weight: 600;">Total impayés</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>Généré le {{ $studentInfo['generated_at'] }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif

    <!-- Loading indicator -->
    <div wire:loading wire:target="loadStudentInfo" class="text-center my-4">
        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div class="card-body p-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <h5 class="text-primary">Chargement des informations...</h5>
                <p class="text-muted mb-0">Veuillez patienter pendant que nous récupérons les données de l'élève</p>
            </div>
        </div>
    </div>

</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #059669;
        box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
    }

    /* Animation pour le dropdown */
    [x-cloak] {
        display: none !important;
    }
</style>
