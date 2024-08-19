<?php

namespace App\Livewire\Application\Finance\Salary\List;

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
    public ?string $date_filter = null, $month_filter = '', $currency_filter = '';

    public function getSalary():void{
        
    }

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
            'salaries' => Salary::query()->paginate(109),
            'total_usd' => 0,
            'total_cdf' => 0
        ]);
    }
}
