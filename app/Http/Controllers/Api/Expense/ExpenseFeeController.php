<?php

namespace App\Http\Controllers\Api\Expense;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Finance\ExpenseFeeFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseFeeController extends Controller
{
    public function getExpenseByMonth(Request $request, string $month)
    {
        return $this->extracted(null, $month);
    }

    public function getExpenseByDate(Request $request, string $date)
    {
        return $this->extracted($date,null);
    }

    /**
     * @param string|null $date
     * @param string|null $month
     * @return JsonResponse
     */
    public function extracted(?string $date,?string $month): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = FeeDataConfiguration::getListCategoryFee(100);
            $expenses = [];
            foreach ($categories as $category) {
                $amount = ExpenseFeeFeature::getAmountTotal(
                    $date,
                    $month,
                    $category->id,
                    null,
                    'USD',
                );
                if ($amount > 0) {
                    $expenses[] = [
                        'name' => $category->name,
                        'amount' => $amount,
                        'currency' => $category->currency
                    ];
                }
            }
            return response()->json([
                'expenses' => $expenses
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
}
