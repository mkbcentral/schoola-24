<div>
    {{-- Styles minimalistes et ergonomiques pour le tableau --}}
    <style>
        /* === VARIABLES ET BASE === */
        .payment-table-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            background-color: var(--bs-body-bg, #ffffff);
            border: 1px solid var(--bs-border-color, #e5e7eb);
        }

        .payment-table {
            border-collapse: collapse;
            width: 100%;
        }

        /* === EN-TÊTE DU TABLEAU === */
        .payment-table thead th {
            background-color: var(--bs-body-bg, #ffffff);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--bs-secondary-color, #6b7280);
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
            white-space: nowrap;
        }

        /* === LIGNES DU TABLEAU === */
        .payment-table tbody tr {
            transition: background-color 0.15s ease;
            border-bottom: 1px solid var(--bs-border-color-translucent, #f3f4f6);
            background-color: var(--bs-body-bg, #ffffff);
        }

        .payment-table tbody tr:hover {
            background-color: var(--bs-tertiary-bg, #f9fafb) !important;
        }

        .payment-table tbody tr:last-child {
            border-bottom: none;
        }

        .payment-table tbody td {
            padding: 0.75rem 0.875rem;
            vertical-align: middle;
            color: var(--bs-body-color, #111827);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* === AVATAR ÉLÈVE === */
        .student-avatar {
            width: 32px;
            height: 32px;
            min-width: 32px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* === NOM ÉLÈVE === */
        .student-name {

            color: var(--bs-emphasis-color, #111827);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .min-w-0 {
            min-width: 0;
        }

        /* === BADGES ÉLÈVE === */
        .student-badge {
            font-size: 0.7rem;
            font-weight: 400;
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            background: var(--bs-secondary-bg, #f3f4f6);
            color: var(--bs-secondary-color, #6b7280);
            border: none;
        }

        /* === BADGE CATÉGORIE === */
        .category-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            border: none;
            display: inline-block;
        }

        /* === MONTANT === */
        .amount-display {
            font-size: 0.95rem;
            font-weight: 600;
            color: #10b981;
        }

        .amount-currency {
            font-size: 0.75rem;
            color: var(--bs-secondary-color, #6b7280);
            font-weight: 400;
        }

        /* === BADGES DE STATUT === */
        .status-badge-paid {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
            border: none;
            display: inline-flex;
            align-items: center;
        }

        .status-badge-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
            border: none;
            display: inline-flex;
            align-items: center;
        }

        /* === DATE ET HEURE === */
        .date-display {
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--bs-emphasis-color, #374151);
        }

        .time-display {
            font-size: 0.7rem;
            color: var(--bs-secondary-color, #9ca3af);
        }

        /* === DROPDOWN ACTIONS === */
        .action-dropdown-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.15s ease;
            border: 1px solid var(--bs-border-color, #e5e7eb);
            background-color: transparent;
            color: var(--bs-secondary-color, #6b7280);
        }

        .action-dropdown-btn:hover {
            background-color: var(--bs-tertiary-bg, #f3f4f6);
            border-color: var(--bs-border-color, #d1d5db);
            color: var(--bs-body-color, #111827);
        }

        .action-dropdown-btn:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .action-dropdown-menu {
            border-radius: 8px;
            border: 1px solid var(--bs-border-color, #e5e7eb);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 0.25rem;
            min-width: 180px;
        }

        /* Force dropdown to open upwards when near bottom */
        .dropdown-menu-end[data-bs-popper] {
            inset: auto auto 0 auto !important;
            transform: translate(0, -2px) !important;
        }

        .action-dropdown-item {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--bs-body-color, #374151);
        }

        .action-dropdown-item:hover {
            background-color: var(--bs-tertiary-bg, #f3f4f6);
            color: var(--bs-emphasis-color, #111827);
        }

        .action-dropdown-item i {
            font-size: 1rem;
            width: 20px;
        }

        .action-dropdown-item.text-primary:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .action-dropdown-item.text-info:hover {
            background-color: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
        }

        .action-dropdown-item.text-warning:hover {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .action-dropdown-item.text-danger:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* === SWITCH PERSONNALISÉ === */
        .custom-switch .form-check-input {
            width: 2.25em;
            height: 1.125em;
            cursor: pointer;
            border-radius: 2em;
            background-color: var(--bs-secondary-bg, #e5e7eb);
            border-color: var(--bs-border-color, #d1d5db);
        }

        .custom-switch .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }

        /* === CHAMPS DE FILTRE === */
        .filter-input {
            border-radius: 10px;
            border: 1.5px solid var(--bs-border-color, #e9ecef);
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
            background-color: var(--bs-body-bg, #ffffff);
            color: var(--bs-body-color, #212529);
        }

        .filter-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            background-color: var(--bs-body-bg, #ffffff);
        }

        .filter-input::placeholder {
            color: var(--bs-secondary-color, #6c757d);
        }

        /* === BADGE TOTAL === */
        .total-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .total-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
        }

        .total-badge i {
            font-size: 1.1rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        [data-theme="dark"] .total-badge {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* === TABLEAU SCROLLABLE === */
        .scrollable-table {
            max-height: 580px;
            overflow-y: auto;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--bs-secondary-bg, #cbd5e0) var(--bs-tertiary-bg, #f7fafc);
        }

        .scrollable-table::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable-table::-webkit-scrollbar-track {
            background: var(--bs-tertiary-bg, #f7fafc);
            border-radius: 3px;
        }

        .scrollable-table::-webkit-scrollbar-thumb {
            background: var(--bs-secondary-bg, #cbd5e0);
            border-radius: 3px;
        }

        .scrollable-table::-webkit-scrollbar-thumb:hover {
            background: var(--bs-secondary-color, #a0aec0);
        }

        /* === EN-TÊTE CARTE === */
        .payment-list-header {
            background-color: var(--bs-body-bg, #ffffff);
            border-bottom: 1px solid var(--bs-border-color, #dee2e6);
        }

        .payment-list-header h5 {
            color: var(--bs-emphasis-color, #212529);
        }

        /* === INPUT GROUP === */
        .input-group-text.filter-input {
            background-color: var(--bs-body-bg, #ffffff);
            color: var(--bs-secondary-color, #6c757d);
        }

        /* === EMPTY STATE === */
        .empty-state-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--bs-tertiary-bg, #f5f7fa) 0%, var(--bs-secondary-bg, #e4e8eb) 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon i {
            color: var(--bs-secondary-color, #6c757d);
            font-size: 2.5rem;
        }

        /* === FOOTER CARTE === */
        .payment-card-footer {
            background-color: var(--bs-body-bg, #ffffff);
            border-top: 1px solid var(--bs-border-color, #dee2e6);
        }

        /* === BORDURE SÉPARATRICE === */
        .filter-border-top {
            border-top: 1px solid var(--bs-border-color, #dee2e6);
        }

        /* === DIVIDER DROPDOWN === */
        .dropdown-divider {
            margin: 0.25rem 0;
            border-color: var(--bs-border-color, #e5e7eb);
        }

        /* === OVERLAY DE CHARGEMENT === */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(2px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        [data-theme="dark"] .loading-overlay {
            background: rgba(0, 0, 0, 0.7);
        }

        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
            border: 3px solid var(--bs-border-color, #e5e7eb);
            border-top-color: #6366f1;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: var(--bs-emphasis-color, #374151);
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>

    <!-- En-tête avec filtres -->
    <div class="card payment-table-card">
        <div class="card-header payment-list-header border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2 d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-list-check text-primary"></i>
                    </div>
                    Liste des paiements
                </h5>
                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                    <i class="bi bi-receipt me-1"></i>{{ $totalCount }}
                </span>
            </div>

            <!-- Filtres -->
            <div class="row g-3">
                <!-- Recherche -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text  border-end-0 filter-input"
                            style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0 filter-input"
                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                            wire:model.live.debounce.300ms="searchPayment" placeholder="Rechercher élève...">
                    </div>
                </div>

                <!-- Filtre date -->
                <div class="col-md-4">
                    <input type="date" class="form-control filter-input" wire:model.live="filterDate">
                </div>

                <!-- Filtre catégorie -->
                <div class="col-md-4">
                    <select class="form-select filter-input" wire:model.live="filterCategoryFeeId">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categoryFees as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Total -->
            @if (!empty($totalsByCurrency))
                <div class="mt-3 pt-3 filter-border-top">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="fw-semibold d-flex align-items-center" style="color: var(--bs-secondary-color);">
                            <i class="bi bi-wallet2 me-2 text-success"></i>
                            Total des paiements validés:
                        </span>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach ($totalsByCurrency as $currency => $total)
                                <span class="badge total-badge">
                                    <i class="bi bi-cash-stack me-1"></i>
                                    {{ number_format($total, 1, ',', ' ') }} {{ $currency }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tableau des paiements -->
        <div class="card-body p-0" style="position: relative; min-height: 400px;">
            <!-- Overlay de chargement -->
            <div wire:loading wire:target="searchPayment,filterDate,filterCategoryFeeId" class="loading-overlay">
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <div class="loading-text">
                        <i class="bi bi-hourglass-split me-2"></i>Chargement des paiements...
                    </div>
                </div>
            </div>

            <div class="table-responsive scrollable-table">
                <table class="table payment-table align-middle mb-0">
                    <thead class="sticky-top" style="z-index: 10;">
                        <tr>
                            <th style="width: 35%;">
                                <i class="bi bi-person-badge me-1"></i>Élève
                            </th>
                            <th style="width: 25%;">
                                <i class="bi bi-tag-fill me-1"></i>Catégorie
                            </th>
                            <th class="text-center" style="width: 20%;">
                                <i class="bi bi-check2-square me-1"></i>Statut
                            </th>
                            <th class="text-center" style="width: 15%;">
                                <i class="bi bi-calendar-event me-1"></i>Date
                            </th>
                            <th class="text-center" style="width: 5%;">
                                <i class="bi bi-three-dots"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr wire:key="payment-{{ $payment->id }}">
                                {{-- Élève --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="student-avatar">
                                            {{ strtoupper(substr($payment->registration->student->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div class="grow min-w-0">
                                            <div class="student-name text-truncate">
                                                {{ $payment->registration->student->name ?? 'N/A' }}
                                            </div>
                                            <span class="student-badge">
                                                <i class="bi bi-door-open"></i>
                                                {{ $payment->registration->classRoom->getOriginalClassRoomName() ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Catégorie et Montant --}}
                                <td>
                                    <div>
                                        <span class="category-badge">
                                            {{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}
                                        </span>
                                        <div class="d-flex align-items-center gap-1 mt-2">
                                            <span class="amount-display">
                                                {{ number_format($payment->scolarFee->amount ?? 0, 0, ',', ' ') }}
                                            </span>
                                            <span class="amount-currency">
                                                {{ $payment->scolarFee->categoryFee->currency ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Statut --}}
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @if ($payment->is_paid)
                                            <span class="status-badge-paid">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span class="ms-1">Payé</span>
                                            </span>
                                        @else
                                            <span class="status-badge-pending">
                                                <i class="bi bi-hourglass-split"></i>
                                                <span class="ms-1">En attente</span>
                                            </span>
                                        @endif
                                        <div class="form-check form-switch custom-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                wire:click="togglePaymentStatus({{ $payment->id }})"
                                                {{ $payment->is_paid ? 'checked' : '' }}
                                                title="{{ $payment->is_paid ? 'Marquer comme non payé' : 'Marquer comme payé' }}">
                                        </div>
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td class="text-center">
                                    <div class="date-display mb-1">
                                        {{ $payment->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="time-display">
                                        <i class="bi bi-clock"></i>
                                        {{ $payment->created_at->format('H:i') }}
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="dropdown dropup">
                                        <button class="btn action-dropdown-btn" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false" title="Actions">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu action-dropdown-menu dropdown-menu-end">
                                            {{-- Modifier --}}
                                            <li>
                                                <a class="dropdown-item action-dropdown-item text-warning"
                                                    wire:click="editPayment({{ $payment->id }})"
                                                    style="cursor: pointer;">
                                                    <i class="bi bi-pencil-fill"></i>
                                                    <span>Modifier</span>
                                                </a>
                                            </li>

                                            @if ($payment->is_paid)
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                {{-- Imprimer --}}
                                                <li>
                                                    <a href="{{ route('print.payment.receipt', $payment->id) }}"
                                                        target="_blank"
                                                        class="dropdown-item action-dropdown-item text-primary">
                                                        <i class="bi bi-printer-fill"></i>
                                                        <span>Imprimer</span>
                                                    </a>
                                                </li>
                                                {{-- SMS --}}
                                                <li>
                                                    <a class="dropdown-item action-dropdown-item text-info"
                                                        wire:click="sendSms({{ $payment->id }})"
                                                        style="cursor: pointer;">
                                                        <i class="bi bi-chat-dots-fill"></i>
                                                        <span>Envoyer SMS</span>
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                {{-- Supprimer --}}
                                                <li>
                                                    <a class="dropdown-item action-dropdown-item text-danger"
                                                        wire:click="confirmDelete({{ $payment->id }})"
                                                        style="cursor: pointer;">
                                                        <i class="bi bi-trash-fill"></i>
                                                        <span>Supprimer</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="empty-state-icon mb-3">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1" style="color: var(--bs-secondary-color);">Aucun
                                            paiement trouvé</h6>
                                        <p class="small mb-0" style="color: var(--bs-secondary-color);">Essayez de
                                            modifier les filtres de recherche</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($this->paymentsPaginated->hasPages())
            <div class="card-footer payment-card-footer border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $this->paymentsPaginated->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal pour l'historique du paiement -->
    @if ($showHistoryModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
            wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-clock-history me-2 text-primary"></i>Historique du paiement
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeHistoryModal"></button>
                    </div>
                    <div class="modal-body">
                        @if (!empty($historyData))
                            <div class="timeline" style="position: relative; padding-left: 30px;">
                                @foreach ($historyData as $entry)
                                    <div class="timeline-item mb-3" style="position: relative;">
                                        <div class="timeline-marker"
                                            style="position: absolute; left: -35px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background-color: #0d6efd;">
                                        </div>
                                        <div class="timeline-content">
                                            <div class="fw-bold">{{ $entry['action'] }}</div>
                                            <small class="text-muted">{{ $entry['date'] }}</small>
                                            @if (isset($entry['details']))
                                                <p class="mb-0">{{ $entry['details'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2 text-muted">Aucun historique disponible pour ce paiement.</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeHistoryModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        // Daily Payment List - SweetAlert pour confirmation de suppression
        $wire.on('delete-payment-dialog', (event) => {
            const data = event[0];
            const amountFormatted = new Intl.NumberFormat('fr-FR').format(data.amount);

            Swal.fire({
                title: 'Voulez-vous vraiment supprimer?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Élève:</strong> ${data.studentName}</p>
                    <p class="mb-2"><strong>Montant:</strong> ${amountFormatted} ${data.currency}</p>
                    <p class="text-danger mt-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cette action est irréversible!</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-2"></i>Oui, supprimer',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Annuler',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deletePayment(data.paymentId);
                }
            });
        });

        $wire.on('payment-deleted', (event) => {
            Swal.fire({
                title: 'Suppression réussie!',
                text: event[0].message,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });

        $wire.on('delete-payment-failed', (event) => {
            Swal.fire({
                title: 'Erreur!',
                text: event[0].message,
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endscript
