<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\School;
use App\Models\SchoolYear;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Relations à charger par défaut (Eager Loading)
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

    public function __construct(private Payment $model) {}

    /**
     * Récupérer tous les paiements avec filtres et eager loading optimisé
     */
    public function getAllWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Utiliser les scopes du modèle mais avec eager loading optimisé
        $query = $this->applyFilters($query, $filters);

        // Eager loading pour éviter N+1
        $query->with(self::DEFAULT_RELATIONS);

        return $query->select('payments.*')
            ->latest('payments.created_at')
            ->paginate($perPage);
    }

    /**
     * Appliquer les filtres de manière optimisée
     */
    private function applyFilters($query, array $filters)
    {
        // Joins nécessaires
        $query->join('registrations', 'registrations.id', 'payments.registration_id')
            ->join('students', 'students.id', 'registrations.student_id')
            ->join('responsible_students', 'responsible_students.id', 'students.responsible_student_id')
            ->join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
            ->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
            ->where('responsible_students.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        // Filtres conditionnels
        if (isset($filters['date'])) {
            $query->whereDate('payments.created_at', $filters['date']);
        }

        if (isset($filters['month'])) {
            $query->where('payments.month', $filters['month']);
        }

        if (isset($filters['categoryFeeId'])) {
            $query->where('category_fees.id', $filters['categoryFeeId']);
        }

        if (isset($filters['feeId'])) {
            $query->where('payments.scolar_fee_id', $filters['feeId']);
        }

        if (isset($filters['sectionId'])) {
            $query->join('class_rooms', 'class_rooms.id', 'registrations.class_room_id')
                ->join('options', 'options.id', 'class_rooms.option_id')
                ->join('sections', 'sections.id', 'options.section_id')
                ->where('sections.id', $filters['sectionId']);
        }

        if (isset($filters['optionId'])) {
            if (! $query->getQuery()->joins || ! collect($query->getQuery()->joins)->contains(fn ($join) => str_contains($join->table, 'options'))) {
                $query->join('class_rooms', 'class_rooms.id', 'registrations.class_room_id')
                    ->join('options', 'options.id', 'class_rooms.option_id');
            }
            $query->where('options.id', $filters['optionId']);
        }

        if (isset($filters['classRoomId'])) {
            $query->where('registrations.class_room_id', $filters['classRoomId']);
        }

        if (isset($filters['isPaid'])) {
            $query->where('payments.is_paid', $filters['isPaid']);
        }

        if (isset($filters['isAccessory'])) {
            $query->where('category_fees.is_accessory', $filters['isAccessory']);
        }

        if (isset($filters['userId'])) {
            $query->where('payments.user_id', $filters['userId']);
        }

        if (isset($filters['key_to_search'])) {
            $query->where('students.name', 'like', '%' . $filters['key_to_search'] . '%');
        }

        return $query;
    }

    /**
     * Récupérer un paiement par son ID avec relations
     */
    public function findById(int $id): ?Payment
    {
        return $this->model->with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * Créer un nouveau paiement
     */
    public function create(array $data): Payment
    {
        $payment = $this->model->create($data);

        // Invalider le cache
        $this->clearCache();

        return $payment->load(self::DEFAULT_RELATIONS);
    }

    /**
     * Mettre à jour un paiement
     */
    public function update(int $id, array $data): bool
    {
        $payment = $this->model->find($id);

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
     * Supprimer un paiement
     */
    public function delete(int $id): bool
    {
        $payment = $this->model->find($id);

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
     * Récupérer les montants totaux par catégorie (avec cache)
     */
    public function getTotalAmountByCategory(?string $month, ?string $date): Collection
    {
        $cacheKey = 'payments.total_by_category.' . ($month ?? 'all') . '.' . ($date ?? 'all');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($month, $date) {
            return $this->model
                ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
                ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
                ->join('registrations', 'payments.registration_id', 'registrations.id')
                ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('payments.is_paid', true)
                ->when($month, fn ($query) => $query->where('payments.month', $month))
                ->when($date, fn ($query) => $query->whereDate('payments.created_at', $date))
                ->select(
                    'category_fees.name as category_name',
                    DB::raw('SUM(scolar_fees.amount) as total_amount'),
                    'category_fees.currency as currency'
                )
                ->groupBy('category_fees.name', 'category_fees.currency')
                ->get();
        });
    }

    /**
     * Récupérer les reçus annuels par catégorie (avec cache)
     */
    public function getYearlyReceiptsByCategory(int $categoryId): Collection
    {
        $cacheKey = 'payments.yearly_receipts.' . $categoryId;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($categoryId) {
            return $this->model
                ->join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
                ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
                ->join('registrations', 'payments.registration_id', 'registrations.id')
                ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('category_fees.id', $categoryId)
                ->where('payments.is_paid', true)
                ->select(
                    'category_fees.name as category_name',
                    DB::raw('payments.month as month'),
                    DB::raw('SUM(scolar_fees.amount) as total_amount'),
                    'category_fees.currency as currency_name'
                )
                ->groupBy('category_fees.name', 'payments.month', 'category_fees.currency')
                ->orderBy('payments.month')
                ->get();
        });
    }

    /**
     * Récupérer les paiements par mois et catégorie
     */
    public function getPaymentsByMonthAndCategory(int $categoryId): Collection
    {
        $cacheKey = 'payments.by_month_category.' . $categoryId;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($categoryId) {
            return $this->model
                ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
                ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
                ->join('registrations', 'payments.registration_id', 'registrations.id')
                ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('category_fees.id', $categoryId)
                ->where('payments.is_paid', true)
                ->select(
                    DB::raw('payments.month as month'),
                    'category_fees.name as category_name',
                    DB::raw('SUM(scolar_fees.amount) as total_amount')
                )
                ->groupBy(DB::raw('payments.month'), 'category_fees.name')
                ->orderBy('payments.month')
                ->get();
        });
    }

    /**
     * Récupérer les paiements non payés
     */
    public function getUnpaidPayments(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(self::DEFAULT_RELATIONS)
            ->where('is_paid', false)
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Récupérer les paiements d'un élève
     */
    public function getStudentPayments(int $studentId, int $schoolYearId): Collection
    {
        return $this->model
            ->with(self::DEFAULT_RELATIONS)
            ->whereHas('registration', function ($query) use ($studentId, $schoolYearId) {
                $query->where('student_id', $studentId)
                    ->where('school_year_id', $schoolYearId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calculer le total des paiements pour une période
     */
    public function getTotalForPeriod(?string $startDate, ?string $endDate, ?int $categoryId = null): float
    {
        $query = $this->model
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->where('payments.is_paid', true);

        if ($startDate) {
            $query->whereDate('payments.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('payments.created_at', '<=', $endDate);
        }

        if ($categoryId) {
            $query->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
                ->where('category_fees.id', $categoryId);
        }

        return (float) $query->sum('scolar_fees.amount');
    }

    /**
     * Récupérer les statistiques de paiement
     */
    public function getPaymentStatistics(array $filters = []): array
    {
        $cacheKey = 'payments.statistics.' . md5(json_encode($filters));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $query = $this->model
                ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
                ->join('registrations', 'payments.registration_id', 'registrations.id')
                ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

            if (isset($filters['month'])) {
                $query->where('payments.month', $filters['month']);
            }

            if (isset($filters['date'])) {
                $query->whereDate('payments.created_at', $filters['date']);
            }

            $paid = (clone $query)->where('payments.is_paid', true)->count();
            $unpaid = (clone $query)->where('payments.is_paid', false)->count();
            $totalAmount = (float) (clone $query)->where('payments.is_paid', true)->sum('scolar_fees.amount');

            return [
                'total_payments' => $paid + $unpaid,
                'paid_payments' => $paid,
                'unpaid_payments' => $unpaid,
                'total_amount' => $totalAmount,
                'average_amount' => $paid > 0 ? round($totalAmount / $paid, 2) : 0,
            ];
        });
    }

    /**
     * Invalider le cache
     */
    private function clearCache(): void
    {
        Cache::tags(['payments'])->flush();
    }
}
