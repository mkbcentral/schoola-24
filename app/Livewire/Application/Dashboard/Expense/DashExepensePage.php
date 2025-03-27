<?php

namespace App\Livewire\Application\Dashboard\Expense;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Enums\RoleType;
use App\Models\CategoryFee;
use App\Models\ExpenseFee;
use App\Models\School;
use Auth;
use Livewire\Component;

class DashExepensePage extends Component
{
    public int $category_fee_filter = 0, $category_expense_id_filter = 0;


    public function mount(): void
    {
        if (Auth::user()->role->name == RoleType::SCHOOL_FINANCE || Auth::user()->role->name == RoleType::SCHOOL_BOSS) {
            $this->category_fee_filter = FeeDataConfiguration::getFirstCategoryFee()?->id ?? 0;
        } else {
            $this->category_fee_filter =  CategoryFee::query()->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', School::DEFAULT_SCHOOL_ID())
                ->where('is_accessory', true)
                ->first()->id;
        }
    }
    public function render()
    {
        return view('livewire.application.dashboard.expense.dash-exepense-page', [
            'expenses' => ExpenseFee::getTotalExpensesByMonth($this->category_fee_filter, $this->category_expense_id_filter),
        ]);
    }
}
