<?php

namespace App\Livewire\Application\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use Livewire\Component;
use Livewire\WithPagination;

class MainControlPaymentPage extends Component
{
    use WithPagination;
    public int $selectedIndex = 0;


    public function mount()
    {
        $categoryFee = FeeDataConfiguration::getListCategoryFeeForCurrentSchool();
        $this->selectedIndex = $categoryFee->id;
    }

    public function changeIndex(int $index)
    {
        $this->selectedIndex = $index;
        $this->dispatch('selectedCategoryFee', $index);
    }


    public function render()
    {
        return view('livewire.application.payment.main-control-payment-page', [
            'lisCategoryFee' => FeeDataConfiguration::getListCategoryFee(100),
        ]);
    }
}
