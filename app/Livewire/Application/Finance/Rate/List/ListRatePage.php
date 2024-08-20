<?php

namespace App\Livewire\Application\Finance\Rate\List;

use App\Domain\Utils\AppMessage;
use App\Models\Rate;
use App\Models\School;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListRatePage extends Component
{
    public string $amount;
    public ?Rate $rateSelected = null;


    public function save(): void
    {
        $inputs = $this->validate(
            ['amount' => 'required|numeric']
        );
        try {
            $inputs['school_id'] = Auth::user()->school_id;
            Rate::create($inputs);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function edit(?Rate $rate)
    {
        $this->rateSelected = $rate;
        $this->amount = $rate->amount;
    }

    public function update()
    {
        $inputs = $this->validate([
            'amount' => 'required|numeric'
        ]);

        try {
            $this->rateSelected->update($inputs);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit(): void
    {
        if ($this->rateSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->rateSelected = null;
        $this->amount = '';
    }

    public function changeStatus(Rate $rate)
    {
        try {
            if ($rate->is_changed == true) {
                $rate->is_changed = false;
            } else {
                $rate->is_changed = true;
            }
            $rate->update();
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function delete(?Rate $rate): void
    {
        try {
            if ($rate->payments->isEmpty()) {
                $rate->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => AppMessage::DATA_DELETED_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function cancelUpdate(): void
    {
        $this->rateSelected = null;
        $this->amount = '';
    }
    public function render()
    {
        return view('livewire.application.finance.rate.list.list-rate-page', [
            'rates' => Rate::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->get()
        ]);
    }
}
