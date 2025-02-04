<?php

namespace App\Livewire\Application\Finance\Recipe\List;

use App\Domain\Utils\AppMessage;
use App\Models\OtherRecipe;
use Auth;
use Exception;
use Livewire\Component;

class ListOtherRecipePage extends Component
{
    public string $description = '', $month_filter = '';
    public float $amount = 0;
    public ?OtherRecipe $otherRecipeSelsected = null;

    public function initForm(): void
    {
        $this->description = '';
        $this->amount = 0;
    }
    public function save(): void
    {
        $inputs = $this->validate(
            ['amount' => 'required|numeric', 'description' => 'required']
        );
        try {
            $inputs['user_id'] = Auth::id();
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
        $this->otherRecipeSelsected = $otherRecipe;
    }

    public function update(): void
    {
        $inputs = $this->validate([
            'amount' => 'required|numeric',
            'description' => 'required'
        ]);
        try {
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
        $this->description = '';
        $this->amount = 0;
        $this->otherRecipeSelsected = null;
    }



    public function delete(?OtherRecipe $otherRecipe): void
    {
        try {
            $otherRecipe->desc->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->month_filter = date('m');
    }

    public function render()
    {
        return view(
            'livewire.application.finance.recipe.list.list-other-recipe-page',
            [
                'otherRecipes' => OtherRecipe::where('user_id', Auth::id())
                    ->whereMonth('created_at', $this->month_filter)
                    ->get()
            ]
        );
    }
}
