<?php

namespace App\Livewire\Application\V2\Fee\Form;

use App\DTOs\Fee\CategoryFeeDTO;
use App\Models\CategoryFee;
use App\Models\SchoolYear;
use App\Services\Fee\CategoryFeeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Component;

class CategoryFeeFormOffcanvas extends Component
{
    public ?int $categoryId = null;
    public string $name = '';
    public ?int $schoolYearId = null;
    public int $schoolId;
    public bool $isStateFee = false;
    public string $currency = 'USD';
    public bool $isPaidInInstallment = false;
    public bool $isPaidForRegistration = false;
    public bool $isForDash = false;
    public bool $isAccessory = false;

    public bool $isEditing = false;
    public Collection $schoolYears;

    protected $listeners = [
        'open-create-category-fee' => 'openCreate',
        'edit-category-fee' => 'openEdit',
        'category-registration-fee-saved' => 'loadSchoolYears',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'schoolYearId' => 'required|exists:school_years,id',
            'currency' => 'required|in:USD,CDF',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom de la catégorie est obligatoire',
        'schoolYearId.required' => 'L\'année scolaire est obligatoire',
        'currency.required' => 'La devise est obligatoire',
    ];

    public function mount()
    {
        $this->schoolId = Auth::user()->school_id;
        $this->schoolYears = collect();
    }

    public function loadSchoolYears()
    {
        $this->schoolYears = SchoolYear::where('school_id', $this->schoolId)
            ->orderBy('name', 'desc')
            ->get();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->loadSchoolYears();
        $this->dispatch('open-offcanvas-category-fee');
    }

    public function openEdit(int $id, CategoryFeeService $service)
    {
        $category = $service->findById($id);

        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->schoolYearId = $category->school_year_id;
            $this->isStateFee = $category->is_state_fee;
            $this->currency = $category->currency;
            $this->isPaidInInstallment = $category->is_paid_in_installment;
            $this->isPaidForRegistration = $category->is_paid_for_registration;
            $this->isForDash = $category->is_for_dash;
            $this->isAccessory = $category->is_accessory;
            $this->isEditing = true;

            $this->loadSchoolYears();
            $this->dispatch('open-offcanvas-category-fee');
        }
    }

    public function edit(int $id, CategoryFeeService $service)
    {
        $category = $service->findById($id);

        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->schoolYearId = $category->school_year_id;
            $this->isStateFee = $category->is_state_fee;
            $this->currency = $category->currency;
            $this->isPaidInInstallment = $category->is_paid_in_installment;
            $this->isPaidForRegistration = $category->is_paid_for_registration;
            $this->isForDash = $category->is_for_dash;
            $this->isAccessory = $category->is_accessory;
            $this->isEditing = true;

            $this->dispatch('open-offcanvas-category-fee');
        }
    }

    public function save(CategoryFeeService $service)
    {
        $this->validate();

        try {
            $dto = CategoryFeeDTO::fromRequest([
                'id' => $this->categoryId,
                'name' => $this->name,
                'school_year_id' => $this->schoolYearId,
                'school_id' => $this->schoolId,
                'is_state_fee' => $this->isStateFee,
                'currency' => $this->currency,
                'is_paid_in_installment' => $this->isPaidInInstallment,
                'is_paid_for_registration' => $this->isPaidForRegistration,
                'is_for_dash' => $this->isForDash,
                'is_accessory' => $this->isAccessory,
            ]);

            if ($this->isEditing) {
                $result = $service->update($this->categoryId, $dto);
                $message = 'Catégorie mise à jour avec succès';
            } else {
                $result = $service->create($dto);
                $message = 'Catégorie créée avec succès';
            }

            if ($result) {
                $this->dispatch('category-fee-saved');
                $this->dispatch('close-offcanvas-category-fee');
                $this->dispatch('success-message', message: $message);
                $this->resetForm();
            } else {
                $this->dispatch('error-message', message: 'Cette catégorie existe déjà');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'categoryId',
            'name',
            'schoolYearId',
            'isStateFee',
            'currency',
            'isPaidInInstallment',
            'isPaidForRegistration',
            'isForDash',
            'isAccessory',
            'isEditing'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.application.v2.fee.form.category-fee-form-offcanvas');
    }
}
