<?php

namespace App\Services\Expense;

use App\Models\OtherSourceExpense;
use App\Models\School;
use Illuminate\Database\Eloquent\Collection;

class OtherSourceExpenseService implements OtherSourceExpenseServiceInterface
{
    /**
     * Get all other source expenses for the current school
     */
    public function getAllBySchool(): Collection
    {
        return OtherSourceExpense::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Create a new other source expense
     */
    public function create(array $data): OtherSourceExpense
    {
        $data['school_id'] = School::DEFAULT_SCHOOL_ID();

        return OtherSourceExpense::create($data);
    }

    /**
     * Update an existing other source expense
     */
    public function update(OtherSourceExpense $otherSourceExpense, array $data): bool
    {
        return $otherSourceExpense->update($data);
    }

    /**
     * Delete an other source expense
     */
    public function delete(OtherSourceExpense $otherSourceExpense): bool
    {
        return $otherSourceExpense->delete();
    }

    /**
     * Find an other source expense by ID
     */
    public function findById(int $id): ?OtherSourceExpense
    {
        return OtherSourceExpense::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->find($id);
    }

    /**
     * Check if source has expenses
     */
    public function hasExpenses(OtherSourceExpense $otherSourceExpense): bool
    {
        return $otherSourceExpense->otherExpenses()->exists();
    }
}
