<?php

namespace App\Services;

use App\DTOs\OtherExpenseDTO;
use App\DTOs\OtherExpenseFilterDTO;
use App\Models\OtherExpense;
use App\Models\SchoolYear;
use App\Services\Contracts\CurrencyExchangeServiceInterface;
use App\Services\Contracts\OtherExpenseServiceInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OtherExpenseService implements OtherExpenseServiceInterface
{
    public function __construct(
        private readonly CurrencyExchangeServiceInterface $currencyService
    ) {}

    /**
     * {@inheritDoc}
     */
    public function create(OtherExpenseDTO $expenseDTO): OtherExpenseDTO
    {
        try {
            DB::beginTransaction();

            $data = $expenseDTO->toArray();
            $data['school_year_id'] = $expenseDTO->schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

            $expense = OtherExpense::create($data);

            DB::commit();

            return OtherExpenseDTO::fromModel($expense);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating other expense: ' . $e->getMessage());
            throw new Exception("Erreur lors de la création de la dépense: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, OtherExpenseDTO $expenseDTO): OtherExpenseDTO
    {
        try {
            DB::beginTransaction();

            $expense = OtherExpense::findOrFail($id);
            $expense->update($expenseDTO->toArray());

            DB::commit();

            return OtherExpenseDTO::fromModel($expense->fresh());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating other expense: ' . $e->getMessage());
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

            $expense = OtherExpense::findOrFail($id);
            $result = $expense->delete();

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting other expense: ' . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de la dépense: " . $e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findById(int $id): ?OtherExpenseDTO
    {
        $expense = OtherExpense::find($id);

        return $expense ? OtherExpenseDTO::fromModel($expense) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(OtherExpenseFilterDTO $filters): LengthAwarePaginator
    {
        $query = OtherExpense::query()
            ->with(['categoryExpense', 'otherSourceExpense'])
            ->join('category_expenses', 'category_expenses.id', 'other_expenses.category_expense_id')
            ->join('schools', 'schools.id', 'category_expenses.school_id')
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        return $query
            ->select('other_expenses.*')
            ->orderBy($filters->sortBy, $filters->sortDirection)
            ->paginate($filters->perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalAmount(OtherExpenseFilterDTO $filters): float
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
    public function getTotalAmountByCurrency(OtherExpenseFilterDTO $filters): array
    {
        $query = OtherExpense::query()
            ->join('category_expenses', 'category_expenses.id', 'other_expenses.category_expense_id')
            ->join('schools', 'schools.id', 'category_expenses.school_id')
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        $result = $query
            ->select(
                DB::raw('SUM(CASE WHEN other_expenses.currency = "USD" THEN other_expenses.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "CDF" THEN other_expenses.amount ELSE 0 END) as total_cdf')
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
    public function getByMonth(OtherExpenseFilterDTO $filters): Collection
    {
        $query = OtherExpense::query()
            ->join('category_expenses', 'other_expenses.category_expense_id', 'category_expenses.id')
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        return $query
            ->select(
                DB::raw('other_expenses.month as month'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "USD" THEN other_expenses.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "CDF" THEN other_expenses.amount ELSE 0 END) as total_cdf'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('other_expenses.month')
            ->orderBy('other_expenses.month', 'asc')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getByCategory(OtherExpenseFilterDTO $filters): Collection
    {
        $query = OtherExpense::query()
            ->join('category_expenses', 'other_expenses.category_expense_id', 'category_expenses.id')
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        $this->applyFilters($query, $filters);

        return $query
            ->select(
                'category_expenses.id as category_id',
                'category_expenses.name as category_name',
                DB::raw('SUM(CASE WHEN other_expenses.currency = "USD" THEN other_expenses.amount ELSE 0 END) as total_usd'),
                DB::raw('SUM(CASE WHEN other_expenses.currency = "CDF" THEN other_expenses.amount ELSE 0 END) as total_cdf'),
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
        $filters = new OtherExpenseFilterDTO(period: $period);

        return OtherExpense::query()
            ->join('category_expenses', 'other_expenses.category_expense_id', 'category_expenses.id')
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when($filters->startDate && $filters->endDate, function ($query) use ($filters) {
                return $query->whereBetween('other_expenses.created_at', [
                    $filters->startDate->startOfDay(),
                    $filters->endDate->endOfDay()
                ]);
            })
            ->select('other_expenses.*')
            ->orderBy('other_expenses.created_at', 'desc')
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function exists(int $id): bool
    {
        return OtherExpense::where('id', $id)->exists();
    }

    /**
     * {@inheritDoc}
     */
    public function getStatistics(OtherExpenseFilterDTO $filters): array
    {
        $totals = $this->getTotalAmountByCurrency($filters);
        $byMonth = $this->getByMonth($filters);
        $byCategory = $this->getByCategory($filters);

        $query = OtherExpense::query()
            ->join('category_expenses', 'category_expenses.id', 'other_expenses.category_expense_id')
            ->where('other_expenses.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

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
    public function export(OtherExpenseFilterDTO $filters, string $format = 'excel'): mixed
    {
        // TODO: Implémenter l'export avec Laravel Excel ou DomPDF
        throw new Exception("L'export n'est pas encore implémenté");
    }

    /**
     * Appliquer les filtres à la requête
     */
    private function applyFilters($query, OtherExpenseFilterDTO $filters): void
    {
        // Filtre par date spécifique
        if ($filters->date) {
            $query->whereDate('other_expenses.created_at', $filters->date);
        }

        // Filtre par plage de dates
        if ($filters->startDate && $filters->endDate) {
            $query->whereBetween('other_expenses.created_at', [
                $filters->startDate->startOfDay(),
                $filters->endDate->endOfDay()
            ]);
        }

        // Filtre par mois
        if ($filters->month) {
            $query->where('other_expenses.month', $filters->month);
        }

        // Filtre par source de dépense
        if ($filters->otherSourceExpenseId) {
            $query->where('other_expenses.other_source_expense_id', $filters->otherSourceExpenseId);
        }

        // Filtre par catégorie de dépense
        if ($filters->categoryExpenseId) {
            $query->where('other_expenses.category_expense_id', $filters->categoryExpenseId);
        }

        // Filtre par devise
        if ($filters->currency) {
            $query->where('other_expenses.currency', $filters->currency);
        }
    }
}
