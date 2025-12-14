<?php

namespace App\Http\Controllers\Api\Expense;

use App\DTOs\ExpenseFilterDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\ExpenseServiceInterface;
use Illuminate\Http\Request;

class ExpenseFeeController extends Controller
{
    public function __construct(
        private ExpenseServiceInterface $expenseService
    ) {}

    public function getExpenseByMonth(Request $request, string $month)
    {
        return $this->extracted(
            null,
            $month,
            $request->category_fee_id,
            $request->category_expense_id,
        );
    }

    public function getExpenseByDate(?Request $request, string $date)
    {
        return $this->extracted(
            $date,
            null,
            $request->category_fee_id,
            $request->category_expense_id,
        );
    }

    public function extracted(?string $date, ?string $month, ?int $categoryFeeId, ?int $categoryExpenseId): \Illuminate\Http\JsonResponse
    {
        try {
            $filtersUsd = new ExpenseFilterDTO(
                date: $date,
                month: $month,
                categoryFeeId: $categoryFeeId,
                categoryExpenseId: $categoryExpenseId,
                currency: 'USD'
            );

            $filtersCdf = new ExpenseFilterDTO(
                date: $date,
                month: $month,
                categoryFeeId: $categoryFeeId,
                categoryExpenseId: $categoryExpenseId,
                currency: 'CDF'
            );

            $amount_usd = $this->expenseService->getTotalAmount($filtersUsd);
            $amount_cdf = $this->expenseService->getTotalAmount($filtersCdf);

            return response()->json([
                [
                    'amount' => $amount_usd,
                    'currency' => 'USD',
                ],
                [
                    'amount' => $amount_cdf,
                    'currency' => 'CDF',
                ],
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage(),
            ]);
        }
    }
}
