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
    public ?CategoryFee $categoryFeeSelected;
    public int $selectedCategoryFeeId = 0;
    public int $option_filter = 0;
    public int $selectedOptionId = 0;
    public int $class_room_filter = 0;

    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'students.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    /**
     * Trier le manière (ASC/DESC)
     * @param mixed $value
     * @return void
     */
    public function sortData(mixed $value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
        $this->resetPage();
    }


    /**
     * Recuprer le categorie de frais selectionné
     * @param int $index
     * @return void
     */
    public function getSelectedCategoryFee(int $index): void
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::find($index);
    }

    public function updatedOptionFilter($val): void
    {
        $this->selectedOptionId = $val;
        $this->class_room_filter = ClassRoom::find($val)->id;
    }

    public function mount(int $categoryFeeId): void
    {
        if (!empty($this)) {
            $this->selectedCategoryFeeId = $categoryFeeId;
        }
        $this->category_fee_filter = FeeDataConfiguration::getFirstCategoryFee()->id;
        $this->categoryFeeSelected = FeeDataConfiguration::getFirstCategoryFee();
        $this->option_filter = SchoolDataFeature::getFirstOption()->id;
        $this->selectedOptionId = SchoolDataFeature::getFirstOption()->id;
        $this->class_room_filter = ClassRoom::find($this->selectedOptionId)->id;
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
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
