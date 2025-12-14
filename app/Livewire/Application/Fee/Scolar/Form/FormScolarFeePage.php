<?php

namespace App\Livewire\Application\Fee\Scolar\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\ScolarFeeForm;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\ScolarFee;
use Exception;
use Livewire\Component;

class FormScolarFeePage extends Component
{
    protected $listeners = [
        'scolrFeeData' => 'getScolarFee',
        'dataFormResed' => 'resetFormData',
        'categoryFeeDataChanged' => 'changeCategoryFee',
        'refreshIndex' => 'sele',
    ];

    public ?ScolarFee $scolarFeeSelected = null;

    public ?CategoryFee $categoryFeeSelected = null;

    public int $option_filter = 0;

    public int $selectedOption = 0;

    public ScolarFeeForm $form;

    public bool $isAllClasse = false;

    // updated isAllClasse
    public function updatedIsAllClasse($val)
    {
        $this->isAllClasse = $val;
    }

    public function resetFormData()
    {
        $this->scolarFeeSelected = null;
        $this->form->reset();
        $this->form->name = $this->categoryFeeSelected->name;
    }

    public function changeCategoryFee(CategoryFee $categoryFeea)
    {
        $this->categoryFeeSelected = $categoryFeea;
    }

    public function updatedOptionFilter($val)
    {
        $this->selectedOption = $val;
    }

    public function updatedFormCategoryFeeId($val)
    {
        $this->dispatch('refreshIndex', $val);
        $this->dispatch('selectedCategoryFee', $val);
        $this->categoryFeeSelected = CategoryFee::findOrFail($val);
    }

    public function getScolarFee(ScolarFee $scolarFee)
    {

        $this->scolarFeeSelected = $scolarFee;
        $this->form->fill($scolarFee->toArray());
        $this->option_filter = $scolarFee?->classRoom->option?->id;
        $this->selectedOption = $scolarFee->classRoom->option->id;
    }

    public function save()
    {

        try {
            if ($this->isAllClasse == true) {
                $classRooms = ClassRoom::where('option_id', $this->selectedOption)->get();
                foreach ($classRooms as $classRoom) {
                    ScolarFee::create([
                        'name' => $this->form->name,
                        'amount' => $this->form->amount,
                        'category_fee_id' => $this->categoryFeeSelected->id,
                        'class_room_id' => $classRoom->id,
                    ]);
                }
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            } else {
                $inputs = $this->validate();
                $inputs['category_fee_id'] = $this->categoryFeeSelected->id;
                $this->form->create($inputs);
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $input = $this->validate();
        try {
            $this->form->update($this->scolarFeeSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->scolarFeeSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('regFeeDataRefreshed');
    }

    public function cancelUpdate()
    {
        $this->scolarFeeSelected = null;
        $this->form->reset();
    }

    public function mount(CategoryFee $categoryFeeSelected)
    {
        $this->categoryFeeSelected = $categoryFeeSelected;
    }

    public function render()
    {
        return view('livewire.application.fee.scolar.form.form-scolar-fee-page');
    }
}
