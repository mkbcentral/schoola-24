<?php

namespace App\Livewire\Application\Finance\Expense\Settings;

use App\Actions\Expense\CreateOtherSourceExpenseAction;
use App\Actions\Expense\UpdateOtherSourceExpenseAction;
use App\Livewire\Forms\OtherSourceExpenseForm;
use App\Services\Expense\OtherSourceExpenseServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class OtherSourceExpenseFormModal extends Component
{
    public OtherSourceExpenseForm $form;

    public bool $isEditMode = false;

    public ?int $sourceId = null;

    /**
     * Open modal for create
     */
    #[On('open-source-modal')]
    public function openModal(): void
    {
        $this->reset();
        $this->isEditMode = false;
        $this->sourceId = null;
    }

    /**
     * Open modal for edit
     */
    #[On('open-edit-source-modal')]
    public function openEditModal(int $sourceId, OtherSourceExpenseServiceInterface $otherSourceExpenseService): void
    {
        $source = $otherSourceExpenseService->findById($sourceId);

        if ($source) {
            $this->sourceId = $source->id;
            $this->form->name = $source->name;
            $this->isEditMode = true;
        }
    }

    /**
     * Save source (create or update)
     */
    public function save(
        CreateOtherSourceExpenseAction $createOtherSourceExpenseAction,
        UpdateOtherSourceExpenseAction $updateOtherSourceExpenseAction
    ): void {
        $this->validate();

        if ($this->isEditMode && $this->sourceId) {
            $result = $updateOtherSourceExpenseAction->execute($this->sourceId, $this->form->toArray());
        } else {
            $result = $createOtherSourceExpenseAction->execute($this->form->toArray());
        }

        if ($result['success']) {
            $this->dispatch('added');
            $this->dispatch('source-saved');
            $this->closeModal();
        } else {
            $this->dispatch('error', message: $result['message']);
        }
    }

    /**
     * Close modal and reset form
     */
    public function closeModal(): void
    {
        $this->reset();
        $this->dispatch('close-source-modal');
    }

    public function render()
    {
        return view('livewire.application.finance.expense.settings.other-source-expense-form-modal');
    }
}
