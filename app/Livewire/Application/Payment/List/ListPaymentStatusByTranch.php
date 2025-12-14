<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\ScolarFee;
use Livewire\Component;

class ListPaymentStatusByTranch extends Component
{
    protected $listeners = [
        'statusTranchDetails' => 'getStatusDetails',
    ];

    public int $selectedOptionId = 0;

    public int $selectedClassRoomId = 0;

    public int $selectedCategoryFeeId = 0;

    public string $filterStatus = 'all';

    public string $sortBy = 'students.name';

    public bool $sortAsc = true;

    public ?ClassRoom $classRoomSelected = null;

    public ?CategoryFee $categoryFeeSelected = null;

    public ?ScolarFee $scolarFee = null;

    /**
     * Change le tri (ASC/DESC) ou la colonne de tri.
     */
    public function sortData(string $column): void
    {
        if ($column === $this->sortBy) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortBy = $column;
            $this->sortAsc = true;
        }
        $this->resetPage();
    }

    public function getStatusDetails(
        int $option,
        int $classRoom,
        int $categoryFeeId
    ): void {
        $this->selectedOptionId = $option;
        $this->selectedClassRoomId = $classRoom;
        $this->selectedCategoryFeeId = $categoryFeeId;
        $this->classRoomSelected = ClassRoom::find($classRoom) ?? null;
        $this->categoryFeeSelected = CategoryFee::find($categoryFeeId) ?? null;
        $this->scolarFee = ScolarFee::where('class_room_id', $classRoom)
            ->where('category_fee_id', $categoryFeeId)
            ->first() ?? 0;
    }

    public function render()
    {
        return view(
            'livewire.application.payment.list.list-payment-status-by-tranch',
            [
                'registrations' => RegistrationFeature::getList(
                    null,
                    null,
                    null,
                    $this->selectedOptionId,
                    $this->selectedClassRoomId,
                    null,
                    null,
                    $this->sortBy,
                    $this->sortAsc,
                    100
                ),
            ]
        );
    }
}
