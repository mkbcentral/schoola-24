<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Models\CategoryFee;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListStudentForControlPaymentPage extends Component
{
    public int $category_fee_filter = 0;
    public ?CategoryFee $categoryFeeSelected;
    #[Url(as: 'q')]
    public $q = '';

    public function mount()
    {
        $categoryFee = FeeDataConfiguration::getListCategoryFeeForCurrentSchool();
        $this->category_fee_filter = $categoryFee->id;
        $this->categoryFeeSelected = $categoryFee;
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-student-for-control-payment-page');
    }
}
