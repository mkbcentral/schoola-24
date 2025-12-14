<?php

namespace App\Livewire\Application\V2\Fee\Form;

use App\DTOs\Fee\CategoryRegistrationFeeDTO;
use App\Models\CategoryRegistrationFee;
use App\Services\Fee\CategoryRegistrationFeeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CategoryRegistrationFeeFormOffcanvas extends Component
{
    public ?int $categoryId = null;
    public string $name = '';
    public bool $isOld = false;
    public int $schoolId;

    public bool $isEditing = false;

    protected $listeners = [
        'open-create-category-registration-fee' => 'openCreate',
        'edit-category-registration-fee' => 'openEdit',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'isOld' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom de la catégorie est obligatoire',
        'name.max' => 'Le nom ne doit pas dépasser 255 caractères',
    ];

    public function mount()
    {
        $this->schoolId = Auth::user()->school_id;
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->dispatch('open-offcanvas-category-registration-fee');
    }

    public function openEdit(int $id, CategoryRegistrationFeeService $service)
    {
        $category = $service->findById($id);

        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->isOld = $category->is_old;
            $this->isEditing = true;

            $this->dispatch('open-offcanvas-category-registration-fee');
        }
    }

    public function edit(int $id, CategoryRegistrationFeeService $service)
    {
        $category = $service->findById($id);

        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->isOld = $category->is_old;
            $this->isEditing = true;

            $this->dispatch('open-offcanvas-category-registration-fee');
        }
    }

    public function save(CategoryRegistrationFeeService $service)
    {
        $this->validate();

        try {
            $dto = CategoryRegistrationFeeDTO::fromRequest([
                'id' => $this->categoryId,
                'name' => $this->name,
                'is_old' => $this->isOld,
                'school_id' => $this->schoolId,
            ]);

            if ($this->isEditing) {
                $result = $service->update($this->categoryId, $dto);
                $message = 'Catégorie mise à jour avec succès';
            } else {
                $result = $service->create($dto);
                $message = 'Catégorie créée avec succès';
            }

            if ($result) {
                $this->dispatch('category-registration-fee-saved');
                $this->dispatch('close-offcanvas-category-registration-fee');
                $this->dispatch('success-message', message: $message);
                $this->reset(['categoryId', 'name', 'isOld', 'isEditing']);
            } else {
                $this->dispatch('error-message', message: 'Cette catégorie existe déjà');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'isOld', 'isEditing']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.application.v2.fee.form.category-registration-fee-form-offcanvas');
    }
}
