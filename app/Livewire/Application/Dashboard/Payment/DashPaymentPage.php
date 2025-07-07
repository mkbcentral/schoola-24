<?php

namespace App\Livewire\Application\Dashboard\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Enums\RoleType;
use App\Models\CategoryFee;
use App\Models\ExpenseFee;
use App\Models\Payment;
use App\Models\School;
use App\Models\SchoolYear;
use Auth;
use DB;
use Livewire\Component;

class DashPaymentPage extends Component
{
    public int $category_fee_filter = 0;

    public function mount(): void
    {
        if (
            Auth::user()->role->name == RoleType::SCHOOL_FINANCE ||
            Auth::user()->role->name == RoleType::SCHOOL_BOSS ||
            Auth::user()->role->name == RoleType::SCHOOL_MANAGER
        ) {
            $this->category_fee_filter = FeeDataConfiguration::getFirstCategoryFee()?->id ?? 0;
        } else {
            $this->category_fee_filter = CategoryFee::query()->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', School::DEFAULT_SCHOOL_ID())
                ->where('is_accessory', true)
                ->first()->id ?? 0;
        }
    }
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {

        return view('livewire.application.dashboard.payment.dash-payment-page', [
            'payments' => Payment::getListReceiptsYear($this->category_fee_filter),
            'categoryFees' => FeeDataConfiguration::getListCategoryFee(100)
        ]);
    }
}
