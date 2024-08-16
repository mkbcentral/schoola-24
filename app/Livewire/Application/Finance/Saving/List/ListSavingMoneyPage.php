<?php

namespace App\Livewire\Application\Finance\Saving\List;

use App\Domain\Features\Finance\SavingMoneyFeature;
use App\Models\SavingMoney;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListSavingMoneyPage extends Component
{
    use WithPagination;
    protected $listeners = [
        'savingMoneyListRefred' => '$refresh'
    ];
    public ?string $date_filter = null, $month_filter = '', $currency_filter = '';

    public function updatedMonthFilter()
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter()
    {
        $this->month_filter = "";
    }


    public function edit(?SavingMoney $savingMoney): void
    {
        $this->dispatch('savingMoneyData', $savingMoney);
    }

    public function delete(SavingMoney $savingMoney)
    {
        try {
            $savingMoney->delete();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->month_filter = date('09');
    }


    public function render()
    {
        return view('livewire.application.finance.saving.list.list-saving-money-page', [
            'savingMoneys' => SavingMoneyFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->currency_filter
            ),
            'total_usd' => SavingMoneyFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                'USD'
            ),
            'total_cdf' => SavingMoneyFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                'CDF'
            )
        ]);
    }
}
