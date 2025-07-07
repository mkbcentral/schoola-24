<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListStudentForControlPaymentPage extends Component
{
    use WithPagination;

    protected $listeners = [
        "selectedCategoryFee" => 'getSelectedCategoryFee'
    ];

    public int $category_fee_filter = 0;
    public ?CategoryFee $categoryFeeSelected = null;
    public int $selectedCategoryFeeId = 0;
    public int $option_filter = 0;
    public int $selectedOptionId = 0;
    public int $class_room_filter = 0;
    public string $selectedMonth = '';

    #[Url(as: 'q')]
    public string $q = '';

    #[Url(as: 'sortBy')]
    public string $sortBy = 'students.name';

    #[Url(as: 'sortAsc')]
    public bool $sortAsc = true;

    /**
     * Change le tri (ASC/DESC) ou la colonne de tri.
     */
    public function sortData(mixed $value): void
    {
        if ($value === $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortBy = $value;
            $this->sortAsc = true;
        }
        $this->resetPage();
    }

    /**
     * Récupère la catégorie de frais sélectionnée.
     */
    public function getSelectedCategoryFee(int $index): void
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::find($index) ?? null;
    }

    /**
     * Met à jour la classe sélectionnée lors du changement d'option.
     */
    public function updatedOptionFilter($val): void
    {
        $this->selectedOptionId = (int)$val;
        $classRoom = ClassRoom::find($this->selectedOptionId);
        $this->class_room_filter = $classRoom?->id ?? 0;
    }

    /**
     * Initialisation du composant.
     */
    public function mount(int $categoryFeeId): void
    {
        $firstCategoryFee = FeeDataConfiguration::getFirstCategoryFee();
        $firstOption = SchoolDataFeature::getFirstOption();

        $this->selectedCategoryFeeId = $categoryFeeId ?: ($firstCategoryFee->id ?? 0);
        $this->category_fee_filter = $firstCategoryFee->id ?? 0;
        $this->categoryFeeSelected = $firstCategoryFee;

        $this->option_filter = $firstOption->id ?? 0;
        $this->selectedOptionId = $firstOption->id ?? 0;

        $classRoom = ClassRoom::find($this->selectedOptionId);
        $this->class_room_filter = $classRoom?->id ?? 0;
    }

    public function openListStatusDetails($m): void
    {
        $this->selectedMonth = $m;
        $this->dispatch(
            'statusDetails',
            $this->selectedMonth,
            $this->selectedOptionId,
            $this->class_room_filter,
            $this->selectedCategoryFeeId
        );
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-student-for-control-payment-page', [
            'registrations' => RegistrationFeature::getList(
                null,
                null,
                null,
                $this->option_filter,
                $this->class_room_filter,
                null,
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                100
            ),
        ]);
    }
}
