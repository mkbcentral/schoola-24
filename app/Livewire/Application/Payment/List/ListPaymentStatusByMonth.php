<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ListPaymentStatusByMonth extends Component
{
    use WithPagination;

    protected $listeners = [
        'statusDetails' => 'getStatusDetails',
    ];

    public string $selectedMonth = '';

    public int $selectedOptionId = 0;

    public int $selectedClassRoomId = 0;

    public int $selectedCategoryFeeId = 0;

    public string $filterStatus = 'all';

    public string $sortBy = 'students.name';

    public bool $sortAsc = true;

    public ?ClassRoom $classRoomSelected = null;

    public ?CategoryFee $categoryFeeSelected = null;

    public ?LengthAwarePaginator $registraions = null;

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

    /**
     * Met à jour les détails de l'état en fonction des paramètres donnés.
     */
    public function getStatusDetails(
        string $month,
        int $option,
        int $classRoom,
        int $categoryFeeId
    ): void {
        $this->selectedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);
        $this->selectedOptionId = $option;
        $this->selectedClassRoomId = $classRoom;
        $this->selectedCategoryFeeId = $categoryFeeId;
        $this->classRoomSelected = ClassRoom::find($classRoom) ?? null;
        $this->categoryFeeSelected = CategoryFee::find($categoryFeeId) ?? null;
        $this->registraions = RegistrationFeature::getList(
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
        );

        // dd($this->selectedOptionId,$this->selectedClassRoomId);
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-payment-status-by-month');
    }
}
