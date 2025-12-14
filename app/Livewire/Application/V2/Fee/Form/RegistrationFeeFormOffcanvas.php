<?php

namespace App\Livewire\Application\V2\Fee\Form;

use App\DTOs\Fee\RegistrationFeeDTO;
use App\Models\RegistrationFee;
use App\Models\CategoryRegistrationFee;
use App\Models\Option;
use App\Models\SchoolYear;
use App\Services\Fee\RegistrationFeeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Component;

class RegistrationFeeFormOffcanvas extends Component
{
    public ?int $feeId = null;
    public string $name = '';
    public float $amount = 0;
    public ?int $optionId = null;
    public ?int $categoryRegistrationFeeId = null;
    public string $currency = 'USD';

    public bool $isEditing = false;
    public int $schoolId;
    public Collection $categoryRegistrationFees;
    public Collection $options;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'optionId' => 'required|exists:options,id',
            'categoryRegistrationFeeId' => 'required|exists:category_registration_fees,id',
            'currency' => 'required|in:USD,CDF',
        ];
    }

    protected $messages = [
        'name.required' => 'Le nom du frais est obligatoire',
        'amount.required' => 'Le montant est obligatoire',
        'amount.numeric' => 'Le montant doit être un nombre',
        'optionId.required' => 'L\'option est obligatoire',
        'categoryRegistrationFeeId.required' => 'La catégorie est obligatoire',
        'currency.required' => 'La devise est obligatoire',
    ];

    public function mount()
    {
        $this->schoolId = Auth::user()->school_id;
        $this->categoryRegistrationFees = collect();
        $this->options = collect();
    }

    public function loadCategoryRegistrationFees()
    {
        $this->categoryRegistrationFees = CategoryRegistrationFee::where('school_id', $this->schoolId)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function loadOptions()
    {
        $this->options = Option::with('section:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'section_id']);
    }

    protected $listeners = [
        'open-create-registration-fee' => 'openCreate',
        'edit-registration-fee' => 'openEdit',
        'category-registration-fee-saved' => 'refreshCategoryRegistrationFees',
    ];

    public function refreshCategoryRegistrationFees()
    {
        $this->loadCategoryRegistrationFees();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->loadCategoryRegistrationFees();
        $this->loadOptions();
        $this->dispatch('open-offcanvas-registration-fee');
    }

    public function openEdit(int $id, RegistrationFeeService $service)
    {
        $fee = $service->findById($id);

        if ($fee) {
            $this->feeId = $fee->id;
            $this->name = $fee->name;
            $this->amount = $fee->amount;
            $this->optionId = $fee->option_id;
            $this->categoryRegistrationFeeId = $fee->category_registration_fee_id;
            $this->currency = $fee->currency;
            $this->isEditing = true;

            $this->loadCategoryRegistrationFees();
            $this->loadOptions();
            $this->dispatch('open-offcanvas-registration-fee');
        }
    }

    public function edit(int $id, RegistrationFeeService $service)
    {
        $fee = $service->findById($id);

        if ($fee) {
            $this->feeId = $fee->id;
            $this->name = $fee->name;
            $this->amount = $fee->amount;
            $this->optionId = $fee->option_id;
            $this->categoryRegistrationFeeId = $fee->category_registration_fee_id;
            $this->currency = $fee->currency;
            $this->isEditing = true;

            $this->dispatch('open-offcanvas-registration-fee');
        }
    }

    public function save(RegistrationFeeService $service)
    {
        $this->validate();

        try {
            $dto = RegistrationFeeDTO::fromRequest([
                'id' => $this->feeId,
                'name' => $this->name,
                'amount' => $this->amount,
                'option_id' => $this->optionId,
                'category_registration_fee_id' => $this->categoryRegistrationFeeId,
                'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
                'currency' => $this->currency,
            ]);

            if ($this->isEditing) {
                $result = $service->update($this->feeId, $dto);
                $message = 'Frais d\'inscription mis à jour avec succès';
            } else {
                $result = $service->create($dto);
                $message = 'Frais d\'inscription créé avec succès';
            }

            if ($result) {
                $this->dispatch('registration-fee-saved');
                $this->dispatch('close-offcanvas-registration-fee');
                $this->dispatch('success-message', message: $message);
                $this->resetForm();
            } else {
                $this->dispatch('error-message', message: 'Ce frais existe déjà');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'feeId',
            'name',
            'amount',
            'optionId',
            'categoryRegistrationFeeId',
            'currency',
            'isEditing'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.application.v2.fee.form.registration-fee-form-offcanvas');
    }
}
