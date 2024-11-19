<?php

namespace App\Livewire\Application\Finance\Salary\List;

use App\Domain\Features\Finance\SalaryFeature;
use App\Domain\Utils\AppMessage;
use App\Models\Salary;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListSalaryPage extends Component
{
    use WithPagination;
    protected $listeners = [
        'SalaryListRefred' => '$refresh',
        'getSalaryDataDetail' => 'getSalary',
    ];
    public ?string $date_filter = '';
    public ?string $month_filter = '';
    public ?string  $currency_filter = '';
    public ?int $per_page = 10;

    public function getSalary(): void {}

    public function updatedMonthFilter(): void
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter(): void
    {
        $this->month_filter = "";
    }
    public function edit(?Salary $salary): void
    {
        $this->dispatch('salaryData', $salary);
    }
    public function addDetail(?Salary $salary): void
    {
        $this->dispatch('salaryData', $salary);
    }
    public function delete(Salary $salary): void
    {
        try {
            $salary->delete();
            $this->dispatch('error', ['message' => AppMessage::DATA_DELETED_FAILLED]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function mount(): void
    {
        $this->month_filter = date('m');
    }
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.salary.list.list-salary-page', [
            'salaries' => SalaryFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->per_page
            ),
            'total_usd' => SalaryFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                'USD'
            ),
            'total_cdf' => SalaryFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                'CDF'
            )
        ]);
    }
}
