<?php

namespace App\Livewire\Application\Fee\Scolar;

use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Component;

class MainScolarFeePage extends Component
{
    protected $listeners = [
        "refreshIndex" => 'changeIndex'
    ];
    public int $selectedIndex = 0;


    public function mount()
    {
        $firstCategoryFee = CategoryFee::firstOrFail();
        $this->selectedIndex = $firstCategoryFee->id;
    }

    public function changeIndex(int $index)
    {
        $this->selectedIndex = $index;
        $this->dispatch('selectedCategoryFee', $index);
    }

    public function render()
    {
        return view('livewire.application.fee.scolar.main-scolar-fee-page', [
            'lisCategoryFee' => CategoryFee::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->get(),
            'listclassRoom' => ClassRoom::all()
        ]);
    }
}
