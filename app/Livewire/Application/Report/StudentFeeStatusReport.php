<?php

namespace App\Livewire\Application\Report;

use Livewire\Component;
use App\Services\StudentFeeStatusService;
use App\Models\CategoryFee;
use App\Models\Option;
use App\Models\ClassRoom;

class StudentFeeStatusReport extends Component
{
    public $categoryFeeIds = [];
    public $optionId = null;
    public $classRoomId = null;
    public $results = [];
    public $allCategories = [];
    public $allOptions = [];
    public $allClassRooms = [];

    public function mount()
    {
        $this->allCategories = CategoryFee::all();
        $this->allOptions = Option::all();
        $this->allClassRooms = ClassRoom::all();
    }

    public function updatedCategoryFeeIds()
    {
        $this->loadResults();
    }
    public function updatedOptionId()
    {
        $this->loadResults();
    }
    public function updatedClassRoomId()
    {
        $this->loadResults();
    }

    public function loadResults()
    {
        $service = new StudentFeeStatusService();
        $this->results = $service->getMonthlyFeeStatusForRegistrations(
            $this->categoryFeeIds,
            null,
            $this->optionId,
            $this->classRoomId
        );
    }

    public function render()
    {
        return view('livewire.reports.student-fee-status-report');
    }
}
