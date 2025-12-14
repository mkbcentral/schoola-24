<?php

namespace App\Livewire\Application\Payment\Report;

use App\Enums\RoleType;
use App\Models\User;
use App\Services\Payment\PaymentReportMailService;
use App\Services\Payment\PaymentReportPdfService;
use App\Services\Payment\PaymentReportService;
use Carbon\Carbon;
use Livewire\Component;

class PaymentReportPage extends Component
{
    public string $reportType = 'daily';

    public ?string $selectedDate = null;

    public ?string $selectedMonth = null;

    public ?string $selectedYear = null;

    public ?string $customStartDate = null;

    public ?string $customEndDate = null;

    public array $report = [];

    // Propriétés du modal d'email
    public $recipients = [];

    public $newEmail = '';

    public $isSendingEmail = false;

    public $showEmailModal = false;

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->loadReport();
    }

    #[\Livewire\Attributes\Computed]
    public function reportData()
    {
        return $this->report;
    }

    public function loadReport()
    {
        $service = new PaymentReportService;

        try {
            $this->report = match ($this->reportType) {
                'daily' => $service->getDailyReport($this->selectedDate),
                'weekly' => $service->getWeeklyReport($this->selectedDate),
                'monthly' => $service->getMonthlyReport(
                    (int) $this->selectedMonth,
                    (int) $this->selectedYear
                ),
                'custom' => $this->customStartDate && $this->customEndDate
                    ? $service->getCustomReport(
                        Carbon::createFromFormat('Y-m-d', $this->customStartDate),
                        Carbon::createFromFormat('Y-m-d', $this->customEndDate)
                    )
                    : [],
                'last_30_days' => $service->getLast30DaysReport(),
                'last_12_months' => $service->getLast12MonthsReport(),
                default => []
            };

            if (! empty($this->report)) {
                $this->dispatch('success', ['message' => 'Rapport chargé avec succès']);
            }
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur lors du chargement du rapport: ' . $e->getMessage()]);
            $this->report = [];
        }
    }

    public function updatedReportType()
    {
        $this->loadReport();
    }

    public function updatedSelectedDate()
    {
        $this->loadReport();
    }

    public function updatedSelectedMonth()
    {
        $this->loadReport();
    }

    public function updatedSelectedYear()
    {
        $this->loadReport();
    }

    public function updateCustomDates()
    {
        if ($this->customStartDate && $this->customEndDate) {
            $this->loadReport();
        } else {
            $this->dispatch('error', ['message' => 'Veuillez sélectionner une période complète']);
        }
    }

    /**
     * Obtenir l'URL de telechargement PDF
     */
    public function getExportPdfUrl(): string
    {
        $params = $this->getReportParams();

        return route('payment-report.pdf.download', $params);
    }

    /**
     * Obtenir l'URL d'apercu PDF
     */
    public function getPreviewPdfUrl(): string
    {
        $params = $this->getReportParams();

        return route('payment-report.pdf.preview', $params);
    }

    /**
     * Obtenir les parametres du rapport
     */
    private function getReportParams(): array
    {
        $params = ['type' => $this->reportType];

        match ($this->reportType) {
            'daily' => $params['date'] = $this->selectedDate,
            'weekly' => $params['date'] = $this->selectedDate,
            'monthly' => [
                $params['month'] = $this->selectedMonth,
                $params['year'] = $this->selectedYear,
            ],
            'custom' => [
                $params['start_date'] = $this->customStartDate,
                $params['end_date'] = $this->customEndDate,
            ],
            default => null
        };

        return $params;
    }

    public function getCurrencyColor($currency)
    {
        return match ($currency) {
            'CDF' => '#28a745',
            'USD' => '#007bff',
            'EUR' => '#6f42c1',
            'GBP' => '#fd7e14',
            default => '#6c757d'
        };
    }

    /**
     * Obtenir la période formatée du rapport
     */
    public function getReportPeriod(): string
    {
        return match ($this->reportType) {
            'daily' => Carbon::parse($this->selectedDate)->format('d/m/Y'),
            'weekly' => 'Semaine du ' . Carbon::parse($this->selectedDate)->format('d/m/Y'),
            'monthly' => Carbon::create($this->selectedYear, $this->selectedMonth, 1)->format('F Y'),
            'custom' => Carbon::parse($this->customStartDate)->format('d/m/Y') . ' - ' .
                Carbon::parse($this->customEndDate)->format('d/m/Y'),
            'last_30_days' => 'Derniers 30 jours',
            'last_12_months' => 'Derniers 12 mois',
            default => ''
        };
    }

    /**
     * Ouvrir le modal d'envoi d'email
     */
    public function openEmailModal()
    {
        $this->loadRecipients();
        // $this->showEmailModal = true;
        // $this->dispatch('open-modal', 'emailReportModal');
    }

    /**
     * Charger les destinataires par défaut
     */
    public function loadRecipients()
    {
        $currentUser = \Illuminate\Support\Facades\Auth::user();
        $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    RoleType::SCHOOL_FINANCE,
                    RoleType::SCHOOL_BOSS,
                    RoleType::SCHOOL_MANAGER,
                ]);
            })
            ->where('school_id', $currentUser->school_id)
            ->where('is_active', true)
            ->whereNotNull('email')
            ->get(['id', 'name', 'email', 'role_id'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name ?? 'N/A',
                    'is_default' => true,
                ];
            })
            ->toArray();

        $this->recipients = $users;
    }

    /**
     * Ajouter un nouvel email
     */
    public function addEmail()
    {
        $this->validate([
            'newEmail' => 'required|email',
        ], [
            'newEmail.required' => 'L\'adresse email est requise',
            'newEmail.email' => 'Veuillez entrer une adresse email valide',
        ]);

        // Vérifier si l'email existe déjà
        $exists = collect($this->recipients)->pluck('email')->contains($this->newEmail);
        if ($exists) {
            $this->dispatch('error', ['message' => 'Cet email est déjà dans la liste']);

            return;
        }

        $this->recipients[] = [
            'id' => null,
            'name' => 'Email personnalisé',
            'email' => $this->newEmail,
            'role' => 'CUSTOM',
            'is_default' => false,
        ];

        $this->newEmail = '';
        $this->dispatch('success', ['message' => 'Email ajouté avec succès']);
    }

    /**
     * Retirer un destinataire
     */
    public function removeRecipient($index)
    {
        if (isset($this->recipients[$index])) {
            unset($this->recipients[$index]);
            $this->recipients = array_values($this->recipients);
            $this->dispatch('success', ['message' => 'Destinataire retiré']);
        }
    }

    /**
     * Envoyer le rapport par email
     */
    public function sendReportByEmail()
    {
        if (empty($this->report)) {
            $this->dispatch('error', ['message' => 'Aucun rapport à envoyer']);

            return;
        }

        if (empty($this->recipients)) {
            $this->dispatch('error', ['message' => 'Aucun destinataire sélectionné']);

            return;
        }

        $this->isSendingEmail = true;

        try {
            // Extraire uniquement les emails
            $emails = collect($this->recipients)->pluck('email')->toArray();

            $mailService = new PaymentReportMailService(new PaymentReportPdfService);

            $success = $mailService->sendReportByEmail(
                $this->report,
                $this->reportType,
                $this->getReportPeriod(),
                $emails
            );

            if ($success) {
                $recipientCount = count($emails);
                $this->dispatch('success', ['message' => "Le rapport a été envoyé avec succès à {$recipientCount} destinataire(s)"]);
                $this->js("bootstrap.Modal.getInstance(document.getElementById('emailReportModal')).hide()");
            } else {
                $this->dispatch('error', ['message' => 'Erreur lors de l\'envoi de l\'email']);
            }
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur: ' . $e->getMessage()]);
        } finally {
            $this->isSendingEmail = false;
        }
    }

    public function render()
    {
        return view('livewire.application.payment.report.payment-report-page');
    }
}
