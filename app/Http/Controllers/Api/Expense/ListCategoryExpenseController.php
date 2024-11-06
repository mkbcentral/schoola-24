<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryExpenseResource;
use App\Models\CategoryExpense;
use App\Models\School;

class ListCategoryExpenseController extends Controller
{
    public function __invoke()
    {
        $cats= CategoryExpense::query()
            ->where('school_id',School::DEFAULT_SCHOOL_ID())
            ->get();
        return response()->json([
            'categories'=> CategoryExpenseResource::collection($cats),
        ]);

    }
}
