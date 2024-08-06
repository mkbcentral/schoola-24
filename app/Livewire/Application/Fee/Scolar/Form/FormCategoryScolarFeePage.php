<?php

namespace App\Livewire\Application\Fee\Scolar\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\CategoryFeeForm;
use App\Models\CategoryFee;
use Exception;
use Livewire\Component;

class FormCategoryScolarFeePage extends Component
{
    protected $listeners = ["categoryScolarFeeData" => "getCategoryScolarFee"];
    public ?CategoryFee $categoryFeeSelected = null;
    public CategoryFeeForm $form;

    public function getCategoryScolarFee(CategoryFee $categoryFee)
    {

        $this->categoryFeeSelected = $categoryFee;
        $this->form->fill($categoryFee->toArray());
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
            $this->form->update($this->categoryFeeSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->categoryFeeSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('regCatFeeScolarDataRefreshed');
        $this->form->reset();
        $this->categoryFeeSelected = null;
    }

    public function cancelUpdate()
    {
        $this->categoryFeeSelected = null;
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.application.fee.scolar.form.form-category-scolar-fee-page');
    }
}
