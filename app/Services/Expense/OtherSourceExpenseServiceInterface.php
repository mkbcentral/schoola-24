<?php

namespace App\Services\Expense;

use App\Models\OtherSourceExpense;
use Illuminate\Database\Eloquent\Collection;

interface OtherSourceExpenseServiceInterface
{
    /**
     * Get all other source expenses for the current school
     */
    public function getAllBySchool(): Collection;

    /**
     * Create a new other source expense
     */
    public function create(array $data): OtherSourceExpense;

    /**
     * Update an existing other source expense
     */
    public function update(OtherSourceExpense $otherSourceExpense, array $data): bool;

    /**
     * Delete an other source expense
     */
    public function delete(OtherSourceExpense $otherSourceExpense): bool;

    /**
     * Find an other source expense by ID
     */
    public function findById(int $id): ?OtherSourceExpense;

    /**
     * Check if source has expenses
     */
    public function hasExpenses(OtherSourceExpense $otherSourceExpense): bool;
}
