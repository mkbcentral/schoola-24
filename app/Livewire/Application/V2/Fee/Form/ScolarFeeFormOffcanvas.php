<?php

namespace App\Livewire\Application\V2\Fee\Form;

use App\DTOs\Fee\ScolarFeeDTO;
use App\Models\ScolarFee;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\School;
use App\Services\Fee\ScolarFeeService;
use Illuminate\Support\Collection;
use Livewire\Component;

class ScolarFeeFormOffcanvas extends Component
{
    public ?int $feeId = null;
    public string $name = '';
    public float $amount = 0;
    public ?int $categoryFeeId = null;
    public ?int $optionId = null;
    public ?int $classRoomId = null;
    public bool $isChanged = false;
    public bool $applyToAllClasses = false;

    public bool $isEditing = false;
    public Collection $categoryFees;
    public Collection $options;
    public Collection $classRooms;

    protected $listeners = [
        'open-create-scolar-fee' => 'openCreate',
        'edit-scolar-fee' => 'openEdit',
        'category-fee-saved' => 'refreshCategoryFees',
    ];

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'categoryFeeId' => 'required|exists:category_fees,id',
            'optionId' => 'required|exists:options,id',
        ];

        // ClassRoomId n'est requis que si applyToAllClasses est désactivé
        if (!$this->applyToAllClasses) {
            $rules['classRoomId'] = 'required|exists:class_rooms,id';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Le nom du frais est obligatoire',
        'amount.required' => 'Le montant est obligatoire',
        'amount.numeric' => 'Le montant doit être un nombre',
        'categoryFeeId.required' => 'La catégorie est obligatoire',
        'optionId.required' => 'L\'option est obligatoire',
        'classRoomId.required' => 'La classe est obligatoire',
    ];

    public function mount()
    {
        $this->categoryFees = collect();
        $this->options = collect();
        $this->classRooms = collect();
    }

    public function loadCategoryFees()
    {
        $currentSchoolId = School::DEFAULT_SCHOOL_ID();
        $this->categoryFees = CategoryFee::where('school_id', $currentSchoolId)
            ->orderBy('name')
            ->get(['id', 'name', 'currency']);
    }

    public function loadOptions()
    {
        $currentSchoolId = School::DEFAULT_SCHOOL_ID();
        $this->options = Option::with('section:id,name,school_id')
            ->whereHas('section', function ($query) use ($currentSchoolId) {
                $query->where('school_id', $currentSchoolId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'section_id']);
    }

    public function updatedOptionId($value)
    {
        $this->classRoomId = null;
        $this->applyToAllClasses = false;
        $this->loadClassRooms();
    }

    public function updatedApplyToAllClasses($value)
    {
        // Réinitialiser la classe sélectionnée si on applique à toutes les classes
        if ($value) {
            $this->classRoomId = null;
        }
    }

    public function loadClassRooms()
    {
        if ($this->optionId) {
            $this->classRooms = ClassRoom::query()
                ->where('option_id', $this->optionId)
                ->orderBy('name')
                ->get(['id', 'name', 'option_id']);
        } else {
            $this->classRooms = collect();
        }
    }

    public function refreshCategoryFees()
    {
        $this->loadCategoryFees();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->loadCategoryFees();
        $this->loadOptions();
        $this->dispatch('open-offcanvas-scolar-fee');
    }

    public function openEdit(int $id, ScolarFeeService $service)
    {
        $fee = $service->findById($id);

        if ($fee) {
            $this->feeId = $fee->id;
            $this->name = $fee->name;
            $this->amount = $fee->amount;
            $this->categoryFeeId = $fee->category_fee_id;
            $this->optionId = $fee->classRoom->option_id ?? null;
            $this->classRoomId = $fee->class_room_id;
            $this->isChanged = $fee->is_changed;
            $this->isEditing = true;

            $this->loadCategoryFees();
            $this->loadOptions();
            if ($this->optionId) {
                $this->loadClassRooms();
            }

            $this->dispatch('open-offcanvas-scolar-fee');
        }
    }

    public function edit(int $id, ScolarFeeService $service)
    {
        $fee = $service->findById($id);

        if ($fee) {
            $this->feeId = $fee->id;
            $this->name = $fee->name;
            $this->amount = $fee->amount;
            $this->categoryFeeId = $fee->category_fee_id;
            $this->optionId = $fee->classRoom->option_id ?? null;
            $this->classRoomId = $fee->class_room_id;
            $this->isChanged = $fee->is_changed;
            $this->isEditing = true;

            if ($this->optionId) {
                $this->loadClassRooms();
            }

            $this->dispatch('open-offcanvas-scolar-fee');
        }
    }

    public function save(ScolarFeeService $service)
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                // Mode édition : comportement normal
                $dto = ScolarFeeDTO::fromRequest([
                    'id' => $this->feeId,
                    'name' => $this->name,
                    'amount' => $this->amount,
                    'category_fee_id' => $this->categoryFeeId,
                    'class_room_id' => $this->classRoomId,
                    'is_changed' => $this->isChanged,
                ]);

                $result = $service->update($this->feeId, $dto);
                $message = 'Frais scolaire mis à jour avec succès';

                if ($result) {
                    $this->dispatch('scolar-fee-saved');
                    $this->dispatch('close-offcanvas-scolar-fee');
                    $this->dispatch('success-message', message: $message);
                    $this->resetForm();
                } else {
                    $this->dispatch('error-message', message: 'Ce frais existe déjà');
                }
            } else {
                // Mode création
                if ($this->applyToAllClasses && $this->optionId) {
                    // Appliquer à toutes les classes de l'option
                    $classRooms = ClassRoom::where('option_id', $this->optionId)->get();

                    if ($classRooms->isEmpty()) {
                        $this->dispatch('error-message', message: 'Aucune classe trouvée pour cette option');
                        return;
                    }

                    $created = 0;
                    foreach ($classRooms as $classRoom) {
                        $dto = ScolarFeeDTO::fromRequest([
                            'name' => $this->name,
                            'amount' => $this->amount,
                            'category_fee_id' => $this->categoryFeeId,
                            'class_room_id' => $classRoom->id,
                            'is_changed' => $this->isChanged,
                        ]);

                        if ($service->create($dto)) {
                            $created++;
                        }
                    }

                    $message = "Frais scolaire appliqué à {$created} classe(s) avec succès";
                    $this->dispatch('scolar-fee-saved');
                    $this->dispatch('close-offcanvas-scolar-fee');
                    $this->dispatch('success-message', message: $message);
                    $this->resetForm();
                } else {
                    // Création normale pour une seule classe
                    $dto = ScolarFeeDTO::fromRequest([
                        'name' => $this->name,
                        'amount' => $this->amount,
                        'category_fee_id' => $this->categoryFeeId,
                        'class_room_id' => $this->classRoomId,
                        'is_changed' => $this->isChanged,
                    ]);

                    $result = $service->create($dto);
                    $message = 'Frais scolaire créé avec succès';

                    if ($result) {
                        $this->dispatch('scolar-fee-saved');
                        $this->dispatch('close-offcanvas-scolar-fee');
                        $this->dispatch('success-message', message: $message);
                        $this->resetForm();
                    } else {
                        $this->dispatch('error-message', message: 'Ce frais existe déjà');
                    }
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['feeId', 'name', 'amount', 'categoryFeeId', 'optionId', 'classRoomId', 'isChanged', 'applyToAllClasses', 'isEditing']);
        $this->classRooms = collect();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.application.v2.fee.form.scolar-fee-form-offcanvas');
    }
}
