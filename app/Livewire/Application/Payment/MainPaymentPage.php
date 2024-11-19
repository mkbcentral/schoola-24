<?php

namespace App\Livewire\Application\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Models\CategoryFee;
use Livewire\Component;
use Livewire\WithPagination;

class MainPaymentPage extends Component
{
    use WithPagination;
    protected $listeners = [
        "refreshIndex" => 'changeIndex'
    ];
    public int $selectedIndex = 0;
    public ?CategoryFee $categoryFeeSelected = null;


    public function mount(): void
    {
        $this->categoryFeeSelected = FeeDataConfiguration::getFirstCategoryFee();
        $this->selectedIndex =  $this->categoryFeeSelected->id;
    }

    public function changeIndex(int $index): void
    {
        $this->selectedIndex = $index;
        $this->dispatch('selectedCategoryFee', $index);
        $this->categoryFeeSelected = CategoryFee::find($index);
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.payment.main-payment-page', [
            'lisCategoryFee' => FeeDataConfiguration::getListCategoryFee(100),
        ]);
    }
}
