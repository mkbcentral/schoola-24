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
    public ?CategoryFee $categoryFeeSelected;


    public function mount()
    {
        $this->categoryFeeSelected = CategoryFee::firstOrFail();
        $this->selectedIndex = $this->categoryFeeSelected->id;
    }

    public function changeIndex(int $index)
    {
        $this->selectedIndex = $index;
        $this->dispatch('selectedCategoryFee', $index);
        $this->categoryFeeSelected = CategoryFee::findOrFail($index);
        $this->dispatch('categoryFeeDataChanged', $this->categoryFeeSelected);
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
