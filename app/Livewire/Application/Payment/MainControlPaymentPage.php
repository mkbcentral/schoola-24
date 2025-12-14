<?php

namespace App\Livewire\Application\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Services\Student\StudentFeeStatusService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MainControlPaymentPage extends Component
{
    use WithPagination;

    public int $selectedIndex = 0;

    public ?CategoryFee $categoryFeeSelected = null;

    public int $category_fee_filter = 0;

    public int $selectedCategoryFeeId = 0;

    public int $option_filter = 0;

    public int $selectedOptionId = 0;

    public int $class_room_filter = 0;

    #[Url(as: 'q')]
    public string $q = '';

    // public $results = [];

    public function updatedOptionFilter($val): void
    {
        $this->selectedOptionId = (int) $val;
        $classRoom = ClassRoom::find($this->selectedOptionId);
        $this->class_room_filter = $classRoom?->id ?? 0;
    }

    public function mount(): void
    {
        $this->categoryFeeSelected = FeeDataConfiguration::getFirstCategoryFee();
        $this->selectedIndex = $this->categoryFeeSelected?->id ?? 0;

        $this->option_filter = $firstOption->id ?? 1;
        $this->selectedOptionId = $firstOption->id ?? 1;

        $classRoom = ClassRoom::find($this->selectedOptionId);
        $this->class_room_filter = $classRoom?->id ?? 1;
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
            'results' => (new StudentFeeStatusService)->getMonthlyFeeStatusForRegistrations(
                [$this->selectedIndex],
                $this->option_filter,
                $this->class_room_filter,
                $this->q
            ),
        ]);
    }
}
