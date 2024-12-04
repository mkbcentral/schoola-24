<?php

namespace App\Livewire\Application\Finance\Salary\List;

use App\Domain\Utils\AppMessage;
use App\Models\CategorySalary;
use App\Models\School;
use App\Models\SchoolYear;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListCategorySalaryPage extends Component
{
    use WithPagination;
    public int $per_page = 5;
    protected $listeners = [
        "catSalaryDataRefreshed" => '$refresh',
    ];
    public function newCategorySalary():void
    {
        //$this->dispatch('initialCatSalaryForm');
    }
    public function edit(CategorySalary $categorySalary): void
    {
        $this->dispatch('categorySalaryData', params: $categorySalary);
    }
    public function delete(?CategorySalary $categorySalary): void
    {
        try {
            if ($categorySalary->salrySatails->isEmpty()) {
                $categorySalary->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.salary.list.list-category-salary-page', [
            'categorySalaries' => CategorySalary::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->paginate(10)
        ]);
    }
}
