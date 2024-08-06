<?php

namespace App\Livewire\Application\Fee\Registration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\CategoryRegistrationFeeForm;
use App\Models\CategoryRegistrationFee;
use Exception;
use Livewire\Component;

class FormCategoryRegistrationFeePage extends Component
{
    protected $listeners = ["categoryRegistrationFeeData" => "getCategoryRegistrationFee"];
    public ?CategoryRegistrationFee $categoryRegistrationFeeSelected = null;
    public CategoryRegistrationFeeForm $form;

    public function getCategoryRegistrationFee(CategoryRegistrationFee $categoryRegistrationFee)
    {
        $this->categoryRegistrationFeeSelected = $categoryRegistrationFee;
        $this->form->fill($categoryRegistrationFee->toArray());
    }

    public function save()
    {
        $input = $this->validate();
        try {
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $input = $this->validate();
        try {
            $this->form->update($this->categoryRegistrationFeeSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->categoryRegistrationFeeSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('regCatFeeDataRefreshed');
        $this->form->reset();
    }

    public function cancelUpdate()
    {
        $this->categoryRegistrationFeeSelected = null;
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.application.fee.registration.form.form-category-registration-fee-page');
    }
}
