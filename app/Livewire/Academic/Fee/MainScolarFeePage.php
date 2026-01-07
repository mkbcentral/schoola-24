<?php

namespace App\Livewire\Academic\Fee;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Component;

class MainScolarFeePage extends Component
{
    protected $listeners = [
        'refreshIndex' => 'changeIndex',
    ];

    public int $selectedIndex = 0;

    public ?CategoryFee $categoryFeeSelected = null;

    public function mount(): void
    {
        $this->categoryFeeSelected = FeeDataConfiguration::getFirstCategoryFee();
        $this->selectedIndex = $this->categoryFeeSelected?->id ?? 0;
    }

    public function changeIndex(int $index): void
    {
        $this->selectedIndex = $index;
        $this->dispatch('selectedCategoryFee', $index);
        $this->categoryFeeSelected = CategoryFee::find($index) ?? null;
        $this->dispatch('categoryFeeDataChanged', $this->categoryFeeSelected);
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.fee.scolar.main-scolar-fee-page', [
            'listCategoryFee' => CategoryFee::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->get(),
            'listclassRoom' => ClassRoom::all(),
        ]);
    }
}
