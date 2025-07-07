<?php

namespace App\Livewire\Application\Widgets\Student;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Helpers\DateFormatHelper;
use App\Models\CategoryFee;
use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;

class StudentPaymentDebtsInfoWidget extends Component
{
    use WithPagination;
    public Registration $registration;
    public ?CategoryFee $selectedCategoryFee;
    public int $category_fee_id = 0;

    //updated category_fee_id
    public function updatedCategoryFeeId(int $value): void
    {
        $this->selectedCategoryFee = CategoryFee::find($value) ?? null;
    }

    public function mount(Registration $registration): void
    {
        $this->registration = $registration;
    }
    public function render()
    {
        return view('livewire.application.widgets.student.student-payment-debts-info-widget', [
            'listCategoryFee' => FeeDataConfiguration::getListCategoryFee(100),
        ]);
    }
}
