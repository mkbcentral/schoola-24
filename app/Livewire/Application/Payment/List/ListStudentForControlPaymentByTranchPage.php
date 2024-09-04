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

class ListStudentForControlPaymentByTranchPage extends Component
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
     * Recuprer le categorie de frais selectionné
     * @param int $index
     * @return void
     */
    public function getSelectedCategoryFee(int $index)
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::find($index);
    }

    public function updatedOptionFilter($val)
    {
        $this->selectedOptionId = $val;
        $this->class_room_filter = ClassRoom::find($val)->id;
    }

    public function mount(int $categoryFeeId)
    {
        $this->selectedCategoryFeeId = $categoryFeeId;
        $categoryFee = FeeDataConfiguration::getListCategoryFeeForCurrentSchool();
        $this->category_fee_filter = $categoryFee->id;
        $this->categoryFeeSelected = $categoryFee;
        $this->option_filter = SchoolDataFeature::getOptionFirstOption()->id;
        $this->selectedOptionId = SchoolDataFeature::getOptionFirstOption()->id;
        $this->class_room_filter = ClassRoom::find($this->selectedOptionId)->id;
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-student-for-control-payment-by-tranch-page', [
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
            'scolarFees' => FeeDataConfiguration::getListScalarFee(
                $this->selectedCategoryFeeId,
                null,
                $this->class_room_filter,
                10
            )
        ]);
    }
}
