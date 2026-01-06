<div>
    <x-navigation.bread-crumb icon='bi bi-cash-stack' label="Gestion des Dépenses">
        <x-navigation.bread-crumb-item label='Dépenses' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="finance.dashboard" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- Switch entre types de dépenses -->
        <x-expense.type-switcher :expenseType="$expenseType" />

        <!-- Statistiques -->
        <x-expense.statistics-cards :statistics="$statistics" />

        <!-- Filtres principaux -->
        <x-expense.quick-filters :date="$date" :filterPeriod="$filterPeriod" :filterCurrency="$filterCurrency" :filterCategoryExpense="$filterCategoryExpense"
            :categoryExpenses="$categoryExpenses" />

        <!-- Offcanvas pour filtres supplémentaires -->
        <x-expense.advanced-filters :expenseType="$expenseType" :dateRange="$dateRange" :dateDebut="$dateDebut" :dateFin="$dateFin"
            :filterMonth="$filterMonth" :filterCategoryFee="$filterCategoryFee" :filterOtherSource="$filterOtherSource" :categoryFees="$categoryFees" :otherSources="$otherSources" />

        <!-- Tableau des dépenses -->
        <x-expense.table :expenses="$expenses" :expenseType="$expenseType" />

        <!-- Taux de change actuel -->
        <div class="alert alert-light mt-3">
            <i class="bi bi-currency-exchange me-2"></i>
            <strong>Taux de change actuel :</strong> 1 USD = {{ app_format_number($currentRate, 0) }} CDF
        </div>
    </x-content.main-content-page>

    <!-- Composant modal pour le formulaire de dépense -->
    @livewire('application.finance.expense.expense-form-modal')

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
</div>

@script
    <script>
        // Expense Management - SweetAlert pour confirmation de suppression
        $wire.on('delete-expense-dialog', (event) => {
            const data = event[0];
            const amountFormatted = new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: data.currency === 'USD' ? 2 : 0,
                maximumFractionDigits: data.currency === 'USD' ? 2 : 0
            }).format(data.amount);

            Swal.fire({
                title: 'Voulez-vous vraiment supprimer?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Description:</strong> ${data.description}</p>
                    <p class="mb-2"><strong>Montant:</strong> ${amountFormatted} ${data.currency}</p>
                    <p class="mb-2"><strong>Catégorie:</strong> ${data.category}</p>
                    <p class="mb-2"><strong>Mois:</strong> ${data.month}</p>
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
                    $wire.deleteExpense(data.expenseId);
                }
            });
        });

        $wire.on('expense-deleted', (event) => {
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

        $wire.on('delete-expense-failed', (event) => {
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

@push('styles')
    <style>
        /* Toggle switch amélioré pour les dépenses */
        .form-check-input.expense-toggle-switch {
            width: 3.5em !important;
            height: 1.8em !important;
            min-width: 3.5em !important;
            cursor: pointer !important;
            border-radius: 2em !important;
            transition: all 0.3s ease !important;
            background-size: contain !important;
        }

        .form-check-input.expense-toggle-switch:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .form-check-input.expense-toggle-switch:not(:checked) {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .form-check-input.expense-toggle-switch:focus {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25) !important;
            border-color: #198754 !important;
        }

        .form-check-input.expense-toggle-switch:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }

        /* Animation du badge */
        .badge {
            transition: all 0.3s ease;
        }

        /* Style pour la ligne validée */
        tr:has(.expense-toggle-switch:checked) {
            background-color: rgba(25, 135, 84, 0.05) !important;
        }

        /* Effet hover sur la ligne */
        tr:has(.expense-toggle-switch):hover {
            background-color: rgba(0, 0, 0, 0.025) !important;
        }

        tr:has(.expense-toggle-switch:checked):hover {
            background-color: rgba(25, 135, 84, 0.08) !important;
        }

        {{-- Styles dark mode intégrés dans themes/_dark.scss --}}
    </style>
@endpush
