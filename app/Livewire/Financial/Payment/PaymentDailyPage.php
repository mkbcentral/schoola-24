<?php

namespace App\Livewire\Financial\Payment;

use App\Models\Payment;
use App\Models\Registration;
use App\Services\Payment\PaymentHistoryService;
use App\Services\Student\StudentDebtTrackerService;
use App\Services\Student\StudentSearchService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentDailyPage extends Component
{
    use WithPagination;

    // Filtres de date
    public $selectedDate;
    public $startDate;
    public $endDate;
    
    // Filtre par catégorie
    public $filterCategoryFeeId = null;
    
    // Modal paiement
    public $showPaymentModal = false;
    
    // Recherche élève
    public $studentSearch = '';
    public $searchResults = [];
    public $showSearchDropdown = false;
    public $isSearching = false;
    
    // Élève sélectionné
    public $selectedStudent = null;
    public $selectedRegistrationId = null;
    public $studentPaymentHistory = [];
    
    // Formulaire de paiement
    public $categoryFeeId = null;
    public $selectedMonth = null;
    public $paymentAmount = null;
    public $isPaid = false;
    public $paymentNote = '';
    
    // Stats
    public $dailyStats = [
        'total_payments' => 0,
        'total_amount' => 0,
        'paid_count' => 0,
        'pending_count' => 0,
        'currency' => 'FC',
    ];
    
    // Services
    private StudentSearchService $studentSearchService;
    private PaymentHistoryService $paymentHistoryService;
    private StudentDebtTrackerService $debtTrackerService;
    
    protected $listeners = [
        'refreshPayments' => '$refresh',
        'paymentCreated' => 'handlePaymentCreated',
    ];
    
    protected $rules = [
        'selectedRegistrationId' => 'required|exists:registrations,id',
        'categoryFeeId' => 'required|exists:category_fees,id',
        'selectedMonth' => 'required|numeric|min:1|max:12',
    ];
    
    protected $messages = [
        'selectedRegistrationId.required' => 'Veuillez sélectionner un élève',
        'categoryFeeId.required' => 'Veuillez sélectionner une catégorie de frais',
        'selectedMonth.required' => 'Veuillez sélectionner un mois',
    ];
    
    /**
     * Normaliser le mois sélectionné
     */
    public function updatedSelectedMonth($value): void
    {
        // Convertir en entier pour supprimer les zéros devant
        if ($value) {
            $this->selectedMonth = (int) $value;
        }
    }
    
    public function boot(
        StudentSearchService $studentSearchService,
        PaymentHistoryService $paymentHistoryService,
        StudentDebtTrackerService $debtTrackerService
    ): void {
        $this->studentSearchService = $studentSearchService;
        $this->paymentHistoryService = $paymentHistoryService;
        $this->debtTrackerService = $debtTrackerService;
    }
    
    public function mount(): void
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        
        // Sélectionner minerval par défaut
        $minerval = \App\Models\CategoryFee::where('school_year_id', \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('name', 'like', '%minerval%')
            ->first();
        $this->filterCategoryFeeId = $minerval?->id;
        
        $this->loadDailyStats();
    }
    
    /**
     * Recherche d'élèves en temps réel
     */
    public function updatedStudentSearch(): void
    {
        $this->isSearching = true;
        
        if (strlen($this->studentSearch) < 2) {
            $this->searchResults = [];
            $this->showSearchDropdown = false;
            $this->isSearching = false;
            return;
        }
        
        try {
            $results = $this->studentSearchService->searchStudents($this->studentSearch);
            
            // Adapter le format des résultats
            $this->searchResults = collect($results)->map(function($result) {
                return [
                    'id' => $result['id'],
                    'name' => $result['student_name'] ?? 'N/A',
                    'code' => $result['code'] ?? 'N/A',
                    'class_room' => $result['class_room'] ?? 'N/A',
                ];
            })->toArray();
            
            $this->showSearchDropdown = count($this->searchResults) > 0;
        } catch (\Exception $e) {
            $this->addError('search', 'Erreur lors de la recherche');
            $this->searchResults = [];
            $this->showSearchDropdown = false;
        } finally {
            $this->isSearching = false;
        }
    }
    
    /**
     * Sélectionner un élève
     */
    public function selectStudent(int $registrationId): void
    {
        try {
            // Fermer immédiatement le dropdown pour une meilleure réactivité
            $this->showSearchDropdown = false;
            $this->isSearching = false;
            
            $registration = Registration::with(['student', 'classRoom.option'])
                ->find($registrationId);
                
            if (!$registration) {
                $this->addError('student', 'Élève non trouvé');
                $this->resetPaymentForm();
                return;
            }
            
            $this->selectedRegistrationId = $registrationId;
            $this->selectedStudent = [
                'id' => $registration->id,
                'name' => $registration->student->name ?? 'N/A',
                'code' => $registration->code ?? 'N/A',
                'class_room' => $registration->classRoom->getOriginalClassRoomName() ?? 'N/A',
                'photo' => $registration->student->photo ?? null,
            ];
            
            $this->studentSearch = $this->selectedStudent['name'];
            $this->searchResults = [];
            
            // Charger l'historique de manière asynchrone
            $this->loadStudentPaymentHistory();
            
            $this->dispatch('student-selected');
        } catch (\Exception $e) {
            $this->addError('student', 'Erreur lors de la sélection de l\'élève');
            $this->resetPaymentForm();
        }
    }
    
    /**
     * Charger l'historique des paiements de l'élève
     */
    public function loadStudentPaymentHistory(): void
    {
        if (!$this->selectedRegistrationId) {
            return;
        }
        
        $this->studentPaymentHistory = $this->paymentHistoryService
            ->getStudentPaymentHistory($this->selectedRegistrationId);
    }
    
    /**
     * Ouvrir le modal de nouveau paiement
     */
    public function openPaymentModal(): void
    {
        $this->resetPaymentForm();
        $this->showPaymentModal = true;
    }
    
    /**
     * Fermer le modal
     */
    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->resetPaymentForm();
    }
    
    /**
     * Enregistrer un nouveau paiement
     */
    public function savePayment(): void
    {
        $this->validate();
        
        try {
            // Convertir le numéro du mois en nom (ex: 9 => 'SEPTEMBRE')
            $monthName = $this->getMonthNameFromNumber($this->selectedMonth);
            
            if (!$monthName) {
                $this->addError('payment', 'Mois invalide sélectionné');
                return;
            }
            
            $result = $this->debtTrackerService->payForMonth(
                registrationId: $this->selectedRegistrationId,
                categoryFeeId: $this->categoryFeeId,
                targetMonth: $monthName,
                paymentData: [
                    'is_paid' => $this->isPaid,
                ]
            );
            
            if ($result['success']) {
                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => $result['message']
                ]);
                
                $this->closePaymentModal();
                $this->loadDailyStats();
                $this->dispatch('refreshPayments');
            } else {
                $this->addError('payment', $result['message']);
            }
            
        } catch (\Exception $e) {
            $this->addError('payment', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }
    
    /**
     * Convertir un numéro de mois en nom de mois en majuscules
     */
    private function getMonthNameFromNumber(int $monthNumber): ?string
    {
        $months = [
            1 => 'JANVIER',
            2 => 'FEVRIER',
            3 => 'MARS',
            4 => 'AVRIL',
            5 => 'MAI',
            6 => 'JUIN',
            7 => 'JUILLET',
            8 => 'AOUT',
            9 => 'SEPTEMBRE',
            10 => 'OCTOBRE',
            11 => 'NOVEMBRE',
            12 => 'DECEMBRE',
        ];
        
        return $months[$monthNumber] ?? null;
    }
    
    /**
     * Réinitialiser le formulaire de paiement
     */
    public function resetPaymentForm(): void
    {
        $this->selectedStudent = null;
        $this->selectedRegistrationId = null;
        $this->studentSearch = '';
        $this->categoryFeeId = null;
        $this->selectedMonth = null;
        $this->paymentAmount = null;
        $this->isPaid = false;
        $this->paymentNote = '';
        $this->studentPaymentHistory = [];
        $this->searchResults = [];
        $this->showSearchDropdown = false;
        $this->resetErrorBag();
    }
    
    /**
     * Charger les statistiques du jour
     */
    public function loadDailyStats(): void
    {
        $date = $this->selectedDate ?? now()->format('Y-m-d');
        
        $query = Payment::whereDate('created_at', $date);
        
        // Appliquer le filtre par catégorie si sélectionné
        if ($this->filterCategoryFeeId) {
            $query->whereHas('scolarFee', function($q) {
                $q->where('category_fee_id', $this->filterCategoryFeeId);
            });
        }
        
        $payments = $query->get();
        
        // Récupérer la devise de la catégorie sélectionnée
        $currency = 'FC'; // Par défaut
        if ($this->filterCategoryFeeId) {
            $category = \App\Models\CategoryFee::find($this->filterCategoryFeeId);
            $currency = $category?->currency ?? 'FC';
        }
        
        $this->dailyStats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->where('is_paid', true)->sum(function($payment) {
                return $payment->scolarFee->amount ?? 0;
            }),
            'paid_count' => $payments->where('is_paid', true)->count(),
            'pending_count' => $payments->where('is_paid', false)->count(),
            'currency' => $currency,
        ];
    }
    
    /**
     * Mettre à jour la date sélectionnée
     */
    public function updatedSelectedDate(): void
    {
        $this->loadDailyStats();
        $this->resetPage();
    }
    
    /**
     * Mettre à jour le filtre de catégorie
     */
    public function updatedFilterCategoryFeeId(): void
    {
        $this->loadDailyStats();
        $this->resetPage();
    }
    
    /**
     * Marquer un paiement comme payé
     */
    public function markAsPaid(int $paymentId): void
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            $this->addError('payment', 'Paiement non trouvé');
            return;
        }
        
        $payment->update(['is_paid' => true]);
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Paiement validé avec succès'
        ]);
        
        $this->loadDailyStats();
        
        // Envoyer automatiquement le SMS au parent
        $this->sendSmsNotification($paymentId);
    }
    
    /**
     * Supprimer un paiement
     */
    public function deletePayment(int $paymentId): void
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            $this->addError('payment', 'Paiement non trouvé');
            return;
        }
        
        if ($payment->is_paid) {
            $this->addError('payment', 'Impossible de supprimer un paiement déjà validé');
            return;
        }
        
        $payment->delete();
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Paiement supprimé avec succès'
        ]);
        
        $this->loadDailyStats();
    }
    
    /**
     * Envoyer un SMS de notification au parent
     */
    public function sendSmsNotification(int $paymentId): void
    {
        try {
            $payment = Payment::with([
                'registration.student.responsibleStudent',
                'registration.classRoom',
                'scolarFee.categoryFee'
            ])->findOrFail($paymentId);
            
            // Récupérer le numéro de téléphone du responsable
            $phone = $payment->registration->student->responsibleStudent->phone ?? null;
            
            if (!$phone) {
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => 'Numéro de téléphone introuvable pour ce parent'
                ]);
                return;
            }
            
            // Construire le message
            $studentName = $payment->registration->student->name ?? 'N/A';
            $categoryName = $payment->scolarFee->categoryFee->name ?? 'N/A';
            $amount = number_format($payment->scolarFee->amount ?? 0, 0, ',', ' ');
            $currency = $payment->scolarFee->categoryFee->currency ?? 'FC';
            $month = \App\Domain\Helpers\DateFormatHelper::getFrenchMonthName($payment->month);
            $status = $payment->is_paid ? 'PAYE' : 'EN ATTENTE';
            
            $message = "Bonjour, Notification de paiement pour {$studentName}. ";
            $message .= "Catégorie: {$categoryName}, Mois: {$month}, ";
            $message .= "Montant: {$amount} {$currency}, Statut: {$status}. ";
            $message .= "Merci.";
            
            // Envoyer le SMS
            \App\Domain\Helpers\SmsNotificationHelper::sendOrangeSMS($phone, $message);
            
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'SMS envoyé avec succès au ' . $phone
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Erreur lors de l\'envoi du SMS: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtenir les catégories de frais disponibles
     */
    public function getCategoryFeesProperty()
    {
        return \App\Models\CategoryFee::where('is_accessory', false)
            ->where('school_year_id', \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->orderBy('name')
            ->get();
    }
    
    /**
     * Obtenir les mois scolaires
     */
    public function getSchoolMonthsProperty()
    {
        return \App\Domain\Helpers\DateFormatHelper::getSchoolFrMonths();
    }
    
    /**
     * Obtenir le montant du frais de la catégorie sélectionnée
     */
    public function getSelectedCategoryFeeAmountProperty()
    {
        if (!$this->categoryFeeId || !$this->selectedRegistrationId) {
            return null;
        }
        
        $registration = \App\Models\Registration::find($this->selectedRegistrationId);
        if (!$registration) {
            return null;
        }
        
        $scolarFee = \App\Models\ScolarFee::where('category_fee_id', $this->categoryFeeId)
            ->where('class_room_id', $registration->class_room_id)
            ->first();
        
        return $scolarFee;
    }
    
    /**
     * Obtenir les paiements du jour
     */
    public function render()
    {
        $query = Payment::with([
                'registration.student', 
                'registration.classRoom.option',
                'scolarFee.categoryFee',
                'user'
            ])
            ->whereDate('created_at', $this->selectedDate);
        
        // Filtre par catégorie de frais
        if ($this->filterCategoryFeeId) {
            $query->whereHas('scolarFee', function($q) {
                $q->where('category_fee_id', $this->filterCategoryFeeId);
            });
        }
        
        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('livewire.financial.payment.payment-daily-page', [
            'payments' => $payments,
            'categoryFees' => $this->categoryFees,
            'schoolMonths' => $this->schoolMonths,
            'selectedCategoryFeeAmount' => $this->selectedCategoryFeeAmount,
        ]);
    }
    
    /**
     * Gérer la création d'un paiement
     */
    public function handlePaymentCreated(): void
    {
        $this->loadDailyStats();
        $this->closePaymentModal();
    }
}
