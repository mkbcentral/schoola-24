<?php

namespace App\Services\Expense;

use App\Models\CategoryExpense;
use Illuminate\Database\Eloquent\Collection;

interface CategoryExpenseServiceInterface
{
    /**
     * Get all category expenses for the current school
     */
    public function getAllBySchool(): Collection;

    /**
     * Create a new category expense
     */
    public function create(array $data): CategoryExpense;

    /**
     * Update an existing category expense
     */
    public function update(CategoryExpense $categoryExpense, array $data): bool;

    /**
     * Delete a category expense
     */
    public function delete(CategoryExpense $categoryExpense): bool;

    /**
     * Find a category expense by ID
     */
    public function findById(int $id): ?CategoryExpense;

    /**
     * Check if category has expenses
     */
    public function hasExpenses(CategoryExpense $categoryExpense): bool;
}
