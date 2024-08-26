<?php

namespace App\Livewire\Application\Finance\Borrowing\List;

use App\Domain\Features\Finance\MoneyBorrowingFeature;
use App\Models\MoneyBorrowing;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListBorrowingPage extends Component
{
    use WithPagination;
    protected $listeners = [
        'moneyBorrowingListRefred' => '$refresh'
    ];
    public ?string $date_filter = null;
    public ?string $month_filter = '';
    public ?string  $currency_filter = '';
    public ?int $per_page = 10;

    public function updatedMonthFilter()
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter()
    {
        $this->month_filter = "";
    }
    public function edit(?MoneyBorrowing $moneyBorrowing)
    {
        $this->dispatch('moneyBorrowingData', $moneyBorrowing);
    }

    public function delete(?MoneyBorrowing $moneyBorrowing)
    {
        try {
            $moneyBorrowing->delete();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->month_filter = date('m');
    }

    public function render()
    {
        return view('livewire.application.finance.borrowing.list.list-borrowing-page', [
            'moneyBorrowings' => MoneyBorrowingFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->currency_filter,
                $this->per_page
            ),
            'total_usd' => MoneyBorrowingFeature::getAmount(
                $this->date_filter,
                $this->month_filter,
                'USD'
            ),
            'total_cdf' => MoneyBorrowingFeature::getAmount(
                $this->date_filter,
                $this->month_filter,
                'CDF'
            )
        ]);
    }
}
