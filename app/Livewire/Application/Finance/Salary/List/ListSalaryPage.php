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

    public function updatedMonthFilter()
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter()
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
    public function delete(Salary $salary)
    {
        try {
            $salary->delete();
            $this->dispatch('error', ['message' => AppMessage::DATA_DELETED_FAILLED]);
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
