<?php

namespace App\Services\Expense;

use App\Models\CategoryExpense;
use App\Models\School;
use Illuminate\Database\Eloquent\Collection;

class CategoryExpenseService implements CategoryExpenseServiceInterface
{
    /**
     * Get all category expenses for the current school
     */
    public function getAllBySchool(): Collection
    {
        return CategoryExpense::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Create a new category expense
     */
    public function create(array $data): CategoryExpense
    {
        $data['school_id'] = School::DEFAULT_SCHOOL_ID();

        return CategoryExpense::create($data);
    }

    /**
     * Update an existing category expense
     */
    public function update(CategoryExpense $categoryExpense, array $data): bool
    {
        return $categoryExpense->update($data);
    }

    /**
     * Delete a category expense
     */
    public function delete(CategoryExpense $categoryExpense): bool
    {
        return $categoryExpense->delete();
    }

    /**
     * Find a category expense by ID
     */
    public function findById(int $id): ?CategoryExpense
    {
        return CategoryExpense::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->find($id);
    }

    /**
     * Check if category has expenses
     */
    public function hasExpenses(CategoryExpense $categoryExpense): bool
    {
        return $categoryExpense->expenseFee()->exists()
            || $categoryExpense->otherExpenses()->exists();
    }
}
