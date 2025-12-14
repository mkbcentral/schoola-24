<?php

namespace App\Services;

use App\DTOs\Payment\PaymentFilterDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\School;
use App\Models\SchoolYear;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Service de gestion des paiements
 * Responsabilités : CRUD + Filtrage avec statistiques par devise
 */
class PaymentService implements PaymentServiceInterface
{
    /**
     * Relations à charger par défaut
     */
    private const DEFAULT_RELATIONS = [
        'registration.student.responsibleStudent',
        'registration.classRoom.option.section',
        'registration.schoolYear',
        'scolarFee.categoryFee',
        'rate',
        'user',
    ];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Tags de cache
     */
    private const CACHE_TAGS = ['payments'];

    /**
     * {@inheritDoc}
     */
    public function create(array $data): Payment
    {
        // Génération automatique du numéro de paiement si non fourni
        if (! isset($data['payment_number'])) {
            $data['payment_number'] = $this->generatePaymentNumber();
        }

        // Définir le rate par défaut si non fourni
        if (! isset($data['rate_id'])) {
            $data['rate_id'] = Rate::DEFAULT_RATE_ID();
        }

        // Définir l'utilisateur connecté si non fourni
        if (! isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        $payment = Payment::create($data);

        // Invalider le cache
        $this->clearCache();

        return $payment->load(self::DEFAULT_RELATIONS);
    }

    /**
     * {@inheritDoc}
     */
    public function find(int $id): ?Payment
    {
        return Payment::with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, array $data): bool
    {
        $payment = Payment::find($id);

        if (! $payment) {
            return false;
        }

        $updated = $payment->update($data);

        if ($updated) {
            $this->clearCache();
        }

        return $updated;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $payment = Payment::find($id);

        if (! $payment) {
            return false;
        }

        $deleted = $payment->delete();

        if ($deleted) {
            $this->clearCache();
        }

        return $deleted;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilteredPayments(PaymentFilterDTO $filters, int $perPage = 15, int $page = 1): PaymentResultDTO
    {
        // Construire la requête de base
        $query = $this->buildBaseQuery();

        // Appliquer les filtres
        $this->applyFilters($query, $filters);

        // Récupérer la liste paginée
        $payments = $query->with(self::DEFAULT_RELATIONS)
            ->select('payments.*')
            ->latest('payments.created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        // Calculer le nombre total
        $totalCount = $payments->total();

        // Calculer les totaux par devise (cachées car changent moins souvent)
        $totalsByCurrency = $this->calculateTotalsByCurrency($filters);

        // Statistiques supplémentaires (cachées car changent moins souvent)
        $statistics = $this->calculateStatistics($filters);

        return new PaymentResultDTO(
            payments: $payments,
            totalCount: $totalCount,
            totalsByCurrency: $totalsByCurrency,
            statistics: $statistics
        );
    }

    /**
     * {@inheritDoc}
     */
    public function clearCache(): void
    {
        // Vérifier si le driver supporte les tags
        if ($this->supportsCacheTags()) {
            Cache::tags(self::CACHE_TAGS)->flush();
        } else {
            // Fallback: vider tout le cache
            Cache::flush();
        }
    }

    /**
     * Méthode helper pour utiliser le cache avec ou sans tags
     *
     * @param string $key
     * @param int $ttl
     * @param \Closure $callback
     * @return mixed
     */
    private function cacheRemember(string $key, int $ttl, \Closure $callback): mixed
    {
        if ($this->supportsCacheTags()) {
            return Cache::tags(self::CACHE_TAGS)->remember($key, $ttl, $callback);
        }

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Vérifier si le driver de cache supporte les tags
     *
     * @return bool
     */
    private function supportsCacheTags(): bool
    {
        $driver = config('cache.default');
        return in_array($driver, ['redis', 'memcached']);
    }

    /**
     * Construire la requête de base avec les joins nécessaires
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildBaseQuery()
    {
        return Payment::query()
            ->join('registrations', 'registrations.id', '=', 'payments.registration_id')
            ->join('students', 'students.id', '=', 'registrations.student_id')
            ->join('responsible_students', 'responsible_students.id', '=', 'students.responsible_student_id')
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'category_fees.id', '=', 'scolar_fees.category_fee_id')
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
    }

    /**
     * Appliquer les filtres à la requête
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param PaymentFilterDTO $filters
     * @return void
     */
    private function applyFilters($query, PaymentFilterDTO $filters): void
    {
        // Filtre par date exacte
        if ($filters->date) {
            $query->whereDate('payments.created_at', $filters->date);
        }

        // Filtre par mois (le champ month contient le nom du mois en français)
        if ($filters->month) {
            $query->where('payments.month', $filters->month);
        }

        // Filtre par période (date de début et date de fin)
        if ($filters->period) {
            $dates = explode(':', $filters->period);
            if (count($dates) === 2) {
                $query->whereBetween('payments.created_at', [$dates[0], $dates[1]]);
            }
        }

        // Filtre par plage de dates prédéfinies
        if ($filters->dateRange) {
            $dateRange = $this->getDateRangeFromPreset($filters->dateRange);
            if ($dateRange) {
                $query->whereBetween('payments.created_at', [$dateRange['start'], $dateRange['end']]);
            }
        }

        // Filtre par catégorie de frais
        if ($filters->categoryFeeId) {
            $query->where('category_fees.id', $filters->categoryFeeId);
        }

        // Filtre par frais scolaire
        if ($filters->feeId) {
            $query->where('payments.scolar_fee_id', $filters->feeId);
        }

        // Filtre par section
        if ($filters->sectionId) {
            $query->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
                ->join('options', 'options.id', '=', 'class_rooms.option_id')
                ->join('sections', 'sections.id', '=', 'options.section_id')
                ->where('sections.id', $filters->sectionId);
        }

        // Filtre par option
        if ($filters->optionId) {
            if (! $this->hasJoin($query, 'options')) {
                $query->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
                    ->join('options', 'options.id', '=', 'class_rooms.option_id');
            }
            $query->where('options.id', $filters->optionId);
        }

        // Filtre par classe
        if ($filters->classRoomId) {
            $query->where('registrations.class_room_id', $filters->classRoomId);
        }

        // Filtre par statut de paiement
        if ($filters->isPaid !== null) {
            $query->where('payments.is_paid', $filters->isPaid);
        }

        // Filtre par utilisateur
        if ($filters->userId) {
            $query->where('payments.user_id', $filters->userId);
        }

        // Filtre par devise
        if ($filters->currency) {
            $query->where('category_fees.currency', $filters->currency);
        }

        // Filtre par recherche (nom d'élève)
        if ($filters->search) {
            $query->where('students.name', 'like', '%' . $filters->search . '%');
        }
    }

    /**
     * Calculer les totaux par devise
     *
     * @param PaymentFilterDTO $filters
     * @return array
     */
    private function calculateTotalsByCurrency(PaymentFilterDTO $filters): array
    {
        $query = $this->buildBaseQuery();
        $this->applyFilters($query, $filters);

        // Toujours filtrer par is_paid = true pour les totaux
        $query->where('payments.is_paid', true);

        $results = $query->select(
            'category_fees.currency',
            DB::raw('SUM(scolar_fees.amount) as total_amount')
        )
            ->groupBy('category_fees.currency')
            ->get();

        $totals = [];
        foreach ($results as $result) {
            $totals[$result->currency] = (float) $result->total_amount;
        }

        return $totals;
    }

    /**
     * Calculer des statistiques supplémentaires
     *
     * @param PaymentFilterDTO $filters
     * @return array
     */
    private function calculateStatistics(PaymentFilterDTO $filters): array
    {
        $query = $this->buildBaseQuery();
        $this->applyFilters($query, $filters);

        $paidCount = (clone $query)->where('payments.is_paid', true)->count();
        $unpaidCount = (clone $query)->where('payments.is_paid', false)->count();

        return [
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
            'payment_rate' => $paidCount + $unpaidCount > 0
                ? round(($paidCount / ($paidCount + $unpaidCount)) * 100, 2)
                : 0,
        ];
    }

    /**
     * Obtenir les dates de début et fin basées sur une plage prédéfinie
     *
     * @param string $preset
     * @return array|null
     */
    private function getDateRangeFromPreset(string $preset): ?array
    {
        $now = now();

        return match ($preset) {
            'this_week' => [
                'start' => $now->startOfWeek()->format('Y-m-d H:i:s'),
                'end' => $now->endOfWeek()->format('Y-m-d H:i:s'),
            ],
            'last_2_weeks' => [
                'start' => $now->subWeeks(2)->startOfWeek()->format('Y-m-d H:i:s'),
                'end' => now()->endOfWeek()->format('Y-m-d H:i:s'),
            ],
            'last_3_weeks' => [
                'start' => $now->subWeeks(3)->startOfWeek()->format('Y-m-d H:i:s'),
                'end' => now()->endOfWeek()->format('Y-m-d H:i:s'),
            ],
            'this_month' => [
                'start' => $now->startOfMonth()->format('Y-m-d H:i:s'),
                'end' => $now->endOfMonth()->format('Y-m-d H:i:s'),
            ],
            'last_3_months' => [
                'start' => $now->subMonths(3)->startOfMonth()->format('Y-m-d H:i:s'),
                'end' => now()->endOfMonth()->format('Y-m-d H:i:s'),
            ],
            'last_6_months' => [
                'start' => $now->subMonths(6)->startOfMonth()->format('Y-m-d H:i:s'),
                'end' => now()->endOfMonth()->format('Y-m-d H:i:s'),
            ],
            'last_9_months' => [
                'start' => $now->subMonths(9)->startOfMonth()->format('Y-m-d H:i:s'),
                'end' => now()->endOfMonth()->format('Y-m-d H:i:s'),
            ],
            default => null,
        };
    }

    /**
     * Vérifier si un join existe déjà dans la requête
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $table
     * @return bool
     */
    private function hasJoin($query, string $table): bool
    {
        $joins = $query->getQuery()->joins ?? [];

        foreach ($joins as $join) {
            if (str_contains($join->table, $table)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Générer un numéro de paiement unique
     *
     * @return string
     */
    private function generatePaymentNumber(): string
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}
