<?php

namespace App\Livewire\Application\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Models\CategoryFee;
use Livewire\Component;
use Livewire\WithPagination;

class MainControlPaymentPage extends Component
{
    use WithPagination;

    public int $selectedIndex = 0;
    public ?CategoryFee $categoryFeeSelected = null;

    public function mount(): void
    {
        $this->categoryFeeSelected = FeeDataConfiguration::getFirstCategoryFee();
        $this->selectedIndex = $this->categoryFeeSelected?->id ?? 0;
    }

    public function changeIndex(int $index): void
    {
        if ($index === $this->selectedIndex) {
            return;
        }

        $categoryFee = CategoryFee::find($index);
        if ($categoryFee) {
            $this->selectedIndex = $index;
            $this->categoryFeeSelected = $categoryFee ?? null;
            $this->dispatch('selectedCategoryFee', $index);
        }
    }
    public function render()
    {
        return view('livewire.application.payment.main-control-payment-page', [
            'lisCategoryFee' => FeeDataConfiguration::getListCategoryFee(100),
        ]);
    }
}
