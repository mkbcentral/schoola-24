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

class DashSynthesePage extends Component
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

        foreach ($this->payments as $payment) {
            $month = format_fr_month_name($payment->month);
            $category = $payment->category_name;
            $amount = $payment->total_amount;

            if (! isset($balances[$month])) {
                $balances[$month] = [];
            }
            if (! isset($balances[$month][$category])) {
                $balances[$month][$category] = ['payments' => 0, 'expenses' => 0, 'balance' => 0];
            }

            $balances[$month][$category]['payments'] += $amount;
        }

        foreach ($this->expenses as $expense) {
            $month = format_fr_month_name($expense->month);
            $category = $expense->category_name;
            $amount = $expense->total_amount;

            if (! isset($balances[$month])) {
                $balances[$month] = [];
            }

            if (! isset($balances[$month][$category])) {
                $balances[$month][$category] = ['payments' => 0, 'expenses' => 0, 'balance' => 0];
            }

            $balances[$month][$category]['expenses'] += $amount;
        }
        foreach ($balances as $month => $categories) {
            foreach ($categories as $category => $data) {
                $balances[$month][$category]['balance'] = $data['payments'] - $data['expenses'];
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

        return view('livewire.application.dashboard.dash-synthese-page', [
            'balances' => $this->balances,
        ]);
    }
}
