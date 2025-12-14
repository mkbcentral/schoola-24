<?php

namespace App\Livewire\Application\Finance\Recipe\List;

use App\Domain\Utils\AppMessage;
use App\Models\OtherRecipe;
use Auth;
use Exception;
use Livewire\Component;

class ListOtherRecipePage extends Component
{
    public string $description = '';

    public string $month_filter = '';

    public string $created_at;

    public string $start_date = '';

    public string $end_date = '';

    public float $amount = 0;

    public bool $is_period = false;

    public ?OtherRecipe $otherRecipeSelsected = null;

    public string $filter_type = 'all';

    public string $filter_start = '';

    public string $filter_end = '';

    public function initForm(): void
    {
        $this->description = '';
        $this->amount = 0;
        $this->created_at = date('Y-m-d');
        $this->start_date = '';
        $this->end_date = '';
        $this->is_period = false;
    }

    public function save(): void
    {
        $rules = [
            'amount' => 'required|numeric',
            'description' => 'required',
        ];
        if ($this->is_period) {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        } else {
            $rules['created_at'] = 'required|date';
        }
        $inputs = $this->validate($rules);
        try {
            $inputs['user_id'] = Auth::id();
            $inputs['is_period'] = $this->is_period;
            $inputs['school_id'] = \App\Models\School::DEFAULT_SCHOOL_ID();
            $inputs['school_year_id'] = \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
            if ($this->is_period) {
                $inputs['start_date'] = $this->start_date;
                $inputs['end_date'] = $this->end_date;
                $inputs['created_at'] = null;
            } else {
                $inputs['start_date'] = null;
                $inputs['end_date'] = null;
            }
            OtherRecipe::create($inputs);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function edit(?OtherRecipe $otherRecipe): void
    {
        $this->description = $otherRecipe->description;
        $this->amount = $otherRecipe->amount;
        $this->is_period = (bool) $otherRecipe->is_period;
        $this->start_date = $otherRecipe?->start_date ?: '';
        $this->end_date = $otherRecipe?->end_date ?: '';
        $this->created_at = $otherRecipe?->created_at ? date('Y-m-d', strtotime($otherRecipe->created_at)) : date('Y-m-d');
        $this->otherRecipeSelsected = $otherRecipe;
    }

    public function update(): void
    {
        $rules = [
            'amount' => 'required|numeric',
            'description' => 'required',
        ];
        if ($this->is_period) {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        } else {
            $rules['created_at'] = 'required|date';
        }
        $inputs = $this->validate($rules);
        try {
            $inputs['is_period'] = $this->is_period;
            $inputs['school_id'] = \App\Models\School::DEFAULT_SCHOOL_ID();
            $inputs['school_year_id'] = \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
            if ($this->is_period) {
                $inputs['start_date'] = $this->start_date;
                $inputs['end_date'] = $this->end_date;
                $inputs['created_at'] = null;
            } else {
                $inputs['start_date'] = null;
                $inputs['end_date'] = null;
            }
            $this->otherRecipeSelsected->update($inputs);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit(): void
    {
        if ($this->otherRecipeSelsected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->initForm();
        $this->otherRecipeSelsected = null;
    }

    public function delete(?OtherRecipe $otherRecipe): void
    {
        try {
            $otherRecipe->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->month_filter = date('m');
        $this->created_at = date('Y-m-d');
        $this->start_date = '';
        $this->end_date = '';
        $this->is_period = false;
    }

    public function cancelUpdate()
    {
        $this->initForm();
        $this->otherRecipeSelsected = null;
    }

    public function render()
    {
        $query = OtherRecipe::query()
            ->where('school_id', \App\Models\School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
        if ($this->filter_type === 'period') {
            if ($this->filter_start && $this->filter_end) {
                $query->where('is_period', true)
                    ->where(function ($q) {
                        $q->where(function ($sub) {
                            $sub->where('start_date', '<=', $this->filter_end)
                                ->where('end_date', '>=', $this->filter_start);
                        });
                    });
            } else {
                $query->where('is_period', true)
                    ->where(function ($q) {
                        $q->whereMonth('start_date', $this->month_filter)
                            ->orWhereMonth('end_date', $this->month_filter);
                    });
            }
        } elseif ($this->filter_type === 'date') {
            if ($this->filter_start && $this->filter_end) {
                $query->where('is_period', false)
                    ->whereBetween('created_at', [$this->filter_start, $this->filter_end]);
            } else {
                $query->where('is_period', false)
                    ->whereMonth('created_at', $this->month_filter);
            }
        } else {
            // all
            $query->where(function ($q) {
                $q->where('is_period', false)->whereMonth('created_at', $this->month_filter)
                    ->orWhere(function ($sub) {
                        $sub->where('is_period', true)
                            ->where(function ($qq) {
                                $qq->whereMonth('start_date', $this->month_filter)
                                    ->orWhereMonth('end_date', $this->month_filter);
                            });
                    });
            });
        }
        $otherRecipes = $query->orderByDesc('created_at')->get();

        return view(
            'livewire.application.finance.recipe.list.list-other-recipe-page',
            [
                'otherRecipes' => $otherRecipes,
            ]
        );
    }
}
