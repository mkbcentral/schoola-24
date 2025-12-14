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
        'bankDepositListRefreshed' => '$refresh',
    ];

    public ?string $date_filter = '';

    public ?string $month_filter = '';

    public ?string $currency_filter = '';

    public ?int $per_page = 10;

    public function newBankDeposit(): void
    {
        $this->dispatch('initialFormBankDeposit');
    }

    public function updatedMonthFilter(): void
    {
        $this->date_filter = null;
    }

    public function updatedDateFilter(): void
    {
        $this->month_filter = '';
    }

    public function edit(?BankDeposit $bankDeposit): void
    {
        $this->dispatch('bankDepositData', $bankDeposit);
    }

    public function delete(BankDeposit $bankDeposit): void
    {
        try {
            $bankDeposit->delete();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount(): void
    {
        $this->month_filter = date('m');
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.bank.list.list-bank-deposit-page', [
            'bankDeposits' => BankDepositFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->currency_filter,
                $this->per_page
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
            ),
        ]);
    }
}
