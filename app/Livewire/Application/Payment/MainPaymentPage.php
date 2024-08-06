<?php

namespace App\Livewire\Application\Payment;

use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Component;
use Livewire\WithPagination;

class MainPaymentPage extends Component
{
    use WithPagination;
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
        return view('livewire.application.payment.main-payment-page', [
            'lisCategoryFee' => CategoryFee::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->get(),
        ]);
    }
}
