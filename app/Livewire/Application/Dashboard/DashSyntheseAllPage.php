<?php

namespace App\Livewire\Application\Dashboard;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Enums\RoleType;
use App\Models\CategoryFee;
use App\Models\ExpenseFee;
use App\Models\Payment;
use App\Models\School;
use Auth;
use Livewire\Component;

class DashSyntheseAllPage extends Component
{
    public $payments;
    public $expenses;
    public $balances;
    public int $category_fee_filter = 0;

    /**
     * Méthode appelée lorsque le filtre de catégorie de frais est mis à jour.
     * Cette méthode déclenche un événement 'refresh-expenses' avec les paramètres des soldes actuels.
     *
     * @return void
     */
    public function updatedCategoryFeeFilter()
    {
        // Recharge les paiements et dépenses selon la nouvelle catégorie sélectionnée
        $this->payments = Payment::getPaymentsByMonthAndCategory($this->category_fee_filter);
        $this->expenses = ExpenseFee::getExpensesByMonthAndCategory($this->category_fee_filter);
        // Recalcule les balances
        $this->calculateBalances();
    }

    public function mount(): void
    {
        if (Auth::user()->role->name == RoleType::SCHOOL_FINANCE || Auth::user()->role->name == RoleType::SCHOOL_BOSS) {
            $this->category_fee_filter = FeeDataConfiguration::getFirstCategoryFee()?->id ?? 0;
        } else {
            $this->category_fee_filter = CategoryFee::query()->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', School::DEFAULT_SCHOOL_ID())
                ->where('is_accessory', true)
                ->first()->id;
        }
    }

    /**
     * Calcule les soldes en fonction des paiements et des dépenses.
     * Cette méthode met à jour la propriété $balances et déclenche un événement 'refresh-expenses'.
     *
     * @return void
     */
    public function calculateBalances()
    {
        $balances = [];

        // Paiements par mois/catégorie
        foreach ($this->payments as $payment) {
            $month = format_fr_month_name($payment->month);
            $category = $payment->category_name;
            $amount = $payment->total_amount;

            if (!isset($balances[$month])) {
                $balances[$month] = [];
            }
            if (!isset($balances[$month][$category])) {
                $balances[$month][$category] = ['payments' => 0, 'expenses' => 0, 'other_expenses' => 0, 'total_expenses' => 0, 'balance' => 0];
            }

            $balances[$month][$category]['payments'] += $amount;
        }

        // Dépenses ExpenseFee par mois/catégorie
        foreach ($this->expenses as $expense) {
            $month = format_fr_month_name($expense->month);
            $category = $expense->category_name;
            $amount = $expense->total_amount;

            if (!isset($balances[$month])) {
                $balances[$month] = [];
            }
            if (!isset($balances[$month][$category])) {
                $balances[$month][$category] = ['payments' => 0, 'expenses' => 0, 'other_expenses' => 0, 'total_expenses' => 0, 'balance' => 0];
            }

            $balances[$month][$category]['expenses'] += $amount;
        }

        // Dépenses OtherExpense par mois (global, pas par catégorie)
        $otherExpensesByMonth = \App\Models\OtherExpense::selectRaw("
            month,
            SUM(CASE WHEN currency = 'CDF' THEN amount / 2850 ELSE 0 END) as cdf_total,
            SUM(CASE WHEN currency = 'USD' THEN amount ELSE 0 END) as usd_total
            ")
            ->groupBy('month')
            ->where('other_source_expense_id', 2)
            ->get()
            ->map(function ($item) {
                $item->total_amount = $item->cdf_total + $item->usd_total;
                return $item;
            })
            ->keyBy(function ($item) {
                return format_fr_month_name($item->month);
            });

        foreach ($otherExpensesByMonth as $month => $otherExpense) {
            // On ajoute la dépense globale à chaque catégorie du mois
            if (isset($balances[$month])) {
                foreach ($balances[$month] as $category => &$data) {
                    $data['other_expenses'] = $otherExpense->total_amount;
                }
                unset($data);
            } else {
                // Si aucun paiement/dépense n'existe pour ce mois, on crée une entrée "Autres"
                $balances[$month]['Autres'] = [
                    'payments' => 0,
                    'expenses' => 0,
                    'other_expenses' => $otherExpense->total_amount,
                    'total_expenses' => $otherExpense->total_amount,
                    'balance' => 0 - $otherExpense->total_amount,
                ];
            }
        }

        // Calcul du total des dépenses et du solde
        foreach ($balances as $month => $categories) {
            foreach ($categories as $category => $data) {
                $totalExpenses = $data['expenses'] + $data['other_expenses'];
                $balances[$month][$category]['total_expenses'] = $totalExpenses;
                $balances[$month][$category]['balance'] = $data['payments'] - $totalExpenses;
            }
        }

        $this->balances = $balances;
        $this->dispatch('refresh-expenses', params: $this->balances);
    }
    public function render()
    {
        $this->payments = Payment::getPaymentsByMonthAndCategory($this->category_fee_filter);
        $this->expenses = ExpenseFee::getExpensesByMonthAndCategory($this->category_fee_filter);
        $this->calculateBalances();
        return view('livewire.application.dashboard.dash-synthese-all-page', [
            'balances' => $this->balances
        ]);
    }
}
