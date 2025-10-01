<?php

namespace App\Livewire\Application\Report;

use Livewire\Component;
use App\Services\MissingRevenueTrackerService;
use App\Models\Option;
use App\Models\CategoryFee;
use App\Models\SchoolYear;

class MissingRevenueReportPage extends Component
{
    public $unpaidList = [];
    public $unpaidClass = null;
    protected $listeners = ['showUnpaidList' => 'loadUnpaidList'];

    public $optionId = null;
    public $categoryFeeId = null;
    public $month = null;
    public $allOptions = [];
    public $allCategories = [];
    public $results = null;
    public $currency = null;

    public function mount()
    {
        $this->allOptions = Option::all();
        $this->allCategories = CategoryFee::query()->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())->get();
    }
    public function showUnpaidList($classRoomName)
    {
        // Si on clique sur la même classe, on ferme la liste
        if ($this->unpaidClass === $classRoomName) {
            $this->unpaidClass = null;
            $this->unpaidList = [];
            return;
        }

        // Sinon, on ouvre la liste pour la nouvelle classe
        $this->unpaidClass = $classRoomName;
        $this->unpaidList = [];

        if (!$this->results || empty($this->results['by_class'])) return;

        $row = collect($this->results['by_class'])->firstWhere('class_room', $classRoomName);
        if (!$row) return;

        // Récupérer les élèves non en ordre pour cette classe spécifique
        $service = new MissingRevenueTrackerService();
        $students = $service->getUnpaidStudentsForClass($this->optionId, $this->categoryFeeId, $classRoomName, $this->month);
        $this->unpaidList = $students;
    }
    public function updatedOptionId()
    {
        // Réinitialiser la liste des élèves non en ordre car les classes peuvent changer
        $this->unpaidClass = null;
        $this->unpaidList = [];
        $this->loadResults();
    }

    public function updatedCategoryFeeId()
    {
        // Réinitialiser la liste des élèves non en ordre car les frais peuvent changer
        $this->unpaidClass = null;
        $this->unpaidList = [];
        $this->setCurrency();
        $this->loadResults();
    }
    public function updatedMonth()
    {
        $this->loadResults();
    }

    public function setCurrency()
    {
        if ($this->categoryFeeId) {
            $categoryFee = CategoryFee::find($this->categoryFeeId);
            $this->currency = $categoryFee ? $categoryFee->currency : null;
        } else {
            $this->currency = null;
        }
    }

    public function loadResults()
    {
        if ($this->optionId && $this->categoryFeeId) {
            $service = new MissingRevenueTrackerService();
            $this->results = $service->getMissingRevenueByClass(
                $this->optionId,
                $this->categoryFeeId,
                null,
                $this->month
            );

            // Actualiser la liste des élèves non en ordre si une classe est sélectionnée
            $this->refreshUnpaidListIfNeeded();
        } else {
            $this->results = null;
            // Réinitialiser la liste si pas de résultats
            $this->unpaidClass = null;
            $this->unpaidList = [];
        }
    }

    /**
     * Actualise la liste des élèves non en ordre pour la classe actuellement sélectionnée
     */
    private function refreshUnpaidListIfNeeded()
    {
        if ($this->unpaidClass && $this->optionId && $this->categoryFeeId) {
            $service = new MissingRevenueTrackerService();
            $students = $service->getUnpaidStudentsForClass(
                $this->optionId,
                $this->categoryFeeId,
                $this->unpaidClass,
                $this->month
            );
            $this->unpaidList = $students;
        }
    }

    public function render()
    {
        return view('livewire.reports.missing-revenue-report-page');
    }
}
