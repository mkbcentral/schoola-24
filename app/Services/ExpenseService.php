<?php

namespace App\Services;

use App\DTOs\ExpenseDTO;
use App\DTOs\ExpenseFilterDTO;
use App\Models\ExpenseFee;
use App\Models\SchoolYear;
use App\Services\Contracts\CurrencyExchangeServiceInterface;
use App\Services\Contracts\ExpenseServiceInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseService implements ExpenseServiceInterface
{
    public function __construct(
        private readonly CurrencyExchangeServiceInterface $currencyService
    ) {}

    /**
     * {@inheritDoc}
     */
    public function create(ExpenseDTO $expenseDTO): ExpenseDTO
    {
        try {
            DB::beginTransaction();

            $data = $expenseDTO->toArray();
            $data['school_year_id'] = $expenseDTO->schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

            $expense = ExpenseFee::create($data);

            DB::commit();

            return ExpenseDTO::fromModel($expense);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating expense: ' . $e->getMessage());
            throw new Exception("Erreur lors de la création de la dépense: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, ExpenseDTO $expenseDTO): ExpenseDTO
    {
        try {
            DB::beginTransaction();

            $expense = ExpenseFee::findOrFail($id);
            $expense->update($expenseDTO->toArray());

            DB::commit();

            return ExpenseDTO::fromModel($expense->fresh());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating expense: ' . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour de la dépense: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();

            $expense = ExpenseFee::findOrFail($id);
            $result = $expense->delete();

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting expense: ' . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de la dépense: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findById(int $id): ?ExpenseDTO
    {
        $expense = ExpenseFee::find($id);

        return $expense ? ExpenseDTO::fromModel($expense) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(ExpenseFilterDTO $filters): LengthAwarePaginator
    {
        $query = ExpenseFee::query()
            ->with(['categoryExpense', 'categoryFee'])
            ->join('category_expenses', 'category_expenses.id', 'expense_fees.category_expense_id')
            ->join('schools', 'schools.id', 'category_expenses.school_id')
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        return $query
            ->select('expense_fees.*')
            ->orderBy($filters->sortBy, $filters->sortDirection)
            ->paginate($filters->perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalAmount(ExpenseFilterDTO $filters): float
    {
        $totals = $this->getTotalAmountByCurrency($filters);

        // Convertir tout en USD
        $totalUSD = $totals['USD'];
        $totalFromCDF = $this->currencyService->convertToUSD($totals['CDF'], 'CDF');

        return round($totalUSD + $totalFromCDF, 2);
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalAmountByCurrency(ExpenseFilterDTO $filters): array
    {
        $query = ExpenseFee::query()
            ->join('category_expenses', 'category_expenses.id', 'expense_fees.category_expense_id')
            ->join('schools', 'schools.id', 'category_expenses.school_id')
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        $result = $query
            ->select(
                DB::raw('SUM(CASE WHEN expense_fees.currency = "USD" THEN expense_fees.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount ELSE 0 END) as total_cdf')
            )
            ->first();

        return [
            'USD' => $result->total_usd ?? 0.0,
            'CDF' => $result->total_cdf ?? 0.0,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getByMonth(ExpenseFilterDTO $filters): Collection
    {
        $query = ExpenseFee::query()
            ->join('category_expenses', 'expense_fees.category_expense_id', 'category_expenses.id')
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        return $query
            ->select(
                DB::raw('expense_fees.month as month'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "USD" THEN expense_fees.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount ELSE 0 END) as total_cdf'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('expense_fees.month')
            ->orderBy('expense_fees.month', 'asc')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getByCategory(ExpenseFilterDTO $filters): Collection
    {
        $query = ExpenseFee::query()
            ->join('category_expenses', 'expense_fees.category_expense_id', 'category_expenses.id')
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        return $query
            ->select(
                'category_expenses.id as category_id',
                'category_expenses.name as category_name',
                DB::raw('SUM(CASE WHEN expense_fees.currency = "USD" THEN expense_fees.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN expense_fees.currency = "CDF" THEN expense_fees.amount ELSE 0 END) as total_cdf'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('category_expenses.id', 'category_expenses.name')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getByPeriod(string $period): Collection
    {
        $filters = new ExpenseFilterDTO(period: $period);

        return ExpenseFee::query()
            ->join('category_expenses', 'expense_fees.category_expense_id', 'category_expenses.id')
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when($filters->startDate && $filters->endDate, function ($query) use ($filters) {
                return $query->whereBetween('expense_fees.created_at', [
                    $filters->startDate->startOfDay(),
                    $filters->endDate->endOfDay()
                ]);
            })
            ->select('expense_fees.*')
            ->orderBy('expense_fees.created_at', 'desc')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function exists(int $id): bool
    {
        return ExpenseFee::where('id', $id)->exists();
    }

    /**
     * {@inheritDoc}
     */
    public function getStatistics(ExpenseFilterDTO $filters): array
    {
        $totals = $this->getTotalAmountByCurrency($filters);
        $byMonth = $this->getByMonth($filters);
        $byCategory = $this->getByCategory($filters);

        $query = ExpenseFee::query()
            ->join('category_expenses', 'category_expenses.id', 'expense_fees.category_expense_id')
            ->where('expense_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        $count = $query->count();
        $average = $count > 0 ? $this->getTotalAmount($filters) / $count : 0;

        return [
            'total_usd' => $totals['USD'],
            'total_cdf' => $totals['CDF'],
            'total_converted_usd' => $this->getTotalAmount($filters),
            'count' => $count,
            'average' => round($average, 2),
            'by_month' => $byMonth,
            'by_category' => $byCategory,
            'currency_rate' => $this->currencyService->getRate('CDF', 'USD'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function export(ExpenseFilterDTO $filters, string $format = 'excel'): mixed
    {
        // TODO: Implémenter l'export avec Laravel Excel ou DomPDF
        throw new Exception("L'export n'est pas encore implémenté");
    }

    /**
     * Appliquer les filtres à la requête
     */
    private function applyFilters($query, ExpenseFilterDTO $filters): void
    {
        // Filtre par date spécifique
        if ($filters->date) {
            $query->whereDate('expense_fees.created_at', $filters->date);
        }

        // Filtre par plage de dates
        if ($filters->startDate && $filters->endDate) {
            $query->whereBetween('expense_fees.created_at', [
                $filters->startDate->startOfDay(),
                $filters->endDate->endOfDay()
            ]);
        }

        // Filtre par mois
        if ($filters->month) {
            $query->where('expense_fees.month', $filters->month);
        }

        // Filtre par catégorie de frais
        if ($filters->categoryFeeId) {
            $query->where('expense_fees.category_fee_id', $filters->categoryFeeId);
        }

        // Filtre par catégorie de dépense
        if ($filters->categoryExpenseId) {
            $query->where('expense_fees.category_expense_id', $filters->categoryExpenseId);
        }

        // Filtre par devise
        if ($filters->currency) {
            $query->where('expense_fees.currency', $filters->currency);
        }
    }
}
