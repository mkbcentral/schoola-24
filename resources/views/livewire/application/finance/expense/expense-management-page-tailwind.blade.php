{{-- Expense Management Page - Version Tailwind CSS --}}
<div>
    <x-navigation.bread-crumb icon='bi bi-cash-stack' label="Gestion des Dépenses">
        <x-navigation.bread-crumb-item label='Dépenses' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- Switch entre types de dépenses -->
        <x-expense.type-switcher-tailwind :expenseType="$expenseType" />

        <!-- Statistiques -->
        <x-expense.statistics-cards-tailwind :statistics="$statistics" />

        <!-- Filtres principaux -->
        <x-expense.quick-filters-tailwind :date="$date" :filterPeriod="$filterPeriod" :filterCurrency="$filterCurrency" :filterCategoryExpense="$filterCategoryExpense"
            :categoryExpenses="$categoryExpenses" />

        <!-- Offcanvas pour filtres supplémentaires -->
        <x-expense.advanced-filters :expenseType="$expenseType" :dateRange="$dateRange" :dateDebut="$dateDebut" :dateFin="$dateFin"
            :filterMonth="$filterMonth" :filterCategoryFee="$filterCategoryFee" :filterOtherSource="$filterOtherSource" :categoryFees="$categoryFees" :otherSources="$otherSources" />

        <!-- Tableau des dépenses -->
        <x-expense.table-tailwind :expenses="$expenses" :expenseType="$expenseType" />

        <!-- Taux de change actuel -->
        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <i class="bi bi-currency-exchange mr-2 text-blue-600 dark:text-blue-400"></i>
            <strong class="text-gray-900 dark:text-gray-100">Taux de change actuel :</strong>
            <span class="text-gray-700 dark:text-gray-300">1 USD = {{ app_format_number($currentRate, 0) }} CDF</span>
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
        /* Toggle switch amélioré pour les dépenses - Version Tailwind */
        .expense-toggle-switch {
            width: 3.5em !important;
            height: 1.8em !important;
            min-width: 3.5em !important;
            cursor: pointer !important;
            border-radius: 2em !important;
            transition: all 0.3s ease !important;
            background-size: contain !important;
        }

        .expense-toggle-switch:checked {
            background-color: #22c55e !important;
            border-color: #22c55e !important;
        }

        .expense-toggle-switch:not(:checked) {
            background-color: #eab308 !important;
            border-color: #eab308 !important;
        }

        .expense-toggle-switch:focus {
            box-shadow: 0 0 0 0.25rem rgba(34, 197, 94, 0.25) !important;
            border-color: #22c55e !important;
        }

        .expense-toggle-switch:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }

        /* Effet sur la ligne validée */
        tr:has(.expense-toggle-switch:checked) {
            background-color: rgba(34, 197, 94, 0.05) !important;
        }

        tr:has(.expense-toggle-switch):hover {
            background-color: rgba(0, 0, 0, 0.025) !important;
        }

        tr:has(.expense-toggle-switch:checked):hover {
            background-color: rgba(34, 197, 94, 0.08) !important;
        }

        /* Dark mode */
        .dark tr:has(.expense-toggle-switch:checked) {
            background-color: rgba(34, 197, 94, 0.1) !important;
        }

        .dark tr:has(.expense-toggle-switch):hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .dark tr:has(.expense-toggle-switch:checked):hover {
            background-color: rgba(34, 197, 94, 0.15) !important;
        }
    </style>
@endpush
