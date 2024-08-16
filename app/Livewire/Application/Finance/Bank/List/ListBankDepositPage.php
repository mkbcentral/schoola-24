<?php

namespace App\Livewire\Application\Finance\Bank\List;

use App\Domain\Features\Finance\BankDepositFeature;
use App\Models\BankDeposit;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListBankDepositPage extends Component
{
    use WithPagination;
    protected $listeners = [
        'bankDepositListRefred' => '$refresh'
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


    public function edit(?BankDeposit $bankDeposit): void
    {
        $this->dispatch('bankDepositData', $bankDeposit);
    }

    public function delete(BankDeposit $bankDeposit)
    {
        try {
            $bankDeposit->delete();
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
        return view('livewire.application.finance.bank.list.list-bank-deposit-page', [
            'bankDeposits' => BankDepositFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->currency_filter
            ),
            'total_usd' => BankDepositFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                'USD'
            ),
            'total_cdf' => BankDepositFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                'CDF'
            )
        ]);
    }
}
