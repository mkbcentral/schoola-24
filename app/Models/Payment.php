<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'month',
        'registration_id',
        'scolar_fee_id',
        'rate_id',
        'user_id',
        'is_paid',
        'created_at',
    ];

    /**
     * Get the registration that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Get the scolarFee that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scolarFee(): BelongsTo
    {
        return $this->belongsTo(ScolarFee::class, 'scolar_fee_id');
    }

    /**
     * Get the rate that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    /**
     * Get the user that owns the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the SmsPayment associated with the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function smsPayment(): HasOne
    {
        return $this->hasOne(SmsPayment::class);
    }

    /**
     * Retourner le montant en CDF
     * @return void
     */
    public function getAmount(): int|float
    {
        return $this->scolarFee->amount;
    }

    /**
     * Scope pour les données liste avec des filtres spécifiques
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $this->reusableScopeData($query)
            ->when(
                $filters['date'],
                function ($query, $f) {
                    return $query->whereDate('payments.created_at', $f);
                }
            )
            ->when(
                $filters['month'],
                function ($query, $f) {
                    return $query->where('payments.month', $f);
                }
            )
            ->when(
                $filters['categoryFeeId'],
                function ($query, $f) {
                    return $query->where('category_fees.id', $f);
                }
            )
            ->when(
                $filters['feeId'],
                function ($query, $f) {
                    return $query->where('payments.scolar_fee_id', $f);
                }
            )
            ->when(
                $filters['sectionId'],
                function ($query, $f) {
                    return $query->where('sections.id', $f);
                }
            )
            ->when(
                $filters['optionId'],
                function ($query, $f) {
                    return $query->where('options.id', $f);
                }
            )
            ->when(
                $filters['classRoomId'],
                function ($query, $f) {
                    return $query->where('registrations.class_room_id', $f);
                }
            )
            ->when(
                $filters['isPaid'],
                function ($query, $f) {
                    return $query->where('payments.is_paid', $f);
                }
            )
            ->when(
                $filters['isAccessory'],
                function ($query, $f) {
                    return $query->where('category_fees.is_accessory', $f);
                }
            )
            ->when(
                $filters['userId'],
                function ($query, $f) {
                    return $query->where('payments.user_id', $f);
                }
            )
            ->when(
                $filters['key_to_search'],
                function ($query, $f) {
                    return $query->where('students.name', 'like', '%' . $f . '%');
                }
            )
            ->with([
                'registration',
                'scolarFee',
                'rate'
            ])
            ->select('payments.*');
    }

    /**
     * Scope pour les requetes non lister (montant,nombre, un paiement )
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotFilter(Builder $query): Builder
    {
        return $this->reusableScopeData($query);
    }


    /**
     * Données du reutilisable dans un scope
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function reusableScopeData(Builder $query): Builder
    {
        return $query
            ->join('registrations', 'registrations.id', 'payments.registration_id')
            ->join('students', 'students.id', 'registrations.student_id')
            ->join('responsible_students', 'responsible_students.id', 'students.responsible_student_id')
            ->join('class_rooms', 'class_rooms.id', 'registrations.class_room_id')
            ->join('options', 'options.id', 'class_rooms.option_id')
            ->join('sections', 'sections.id', 'options.section_id')
            ->join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
            ->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
            ->where('responsible_students.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
    }

    public static function getTotalAmountByCategoryForMonthOrDate(string|null $month, string|null $date)
    {
        return self::join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->join('rates', 'payments.rate_id', '=', 'rates.id')
            ->select(
                'category_fees.name as category_name',
                DB::raw('SUM(scolar_fees.amount) as total_amount'),
                'category_fees.currency as currency'
            )
            ->when($month, function ($query, $month) {
                return $query->where('payments.month', $month);
            })
            ->when($date, function ($query, $date) {
                return $query->whereDate('payments.created_at', $date);
            })
            ->where('payments.is_paid', true)
            ->groupBy('category_fees.name', 'category_fees.currency')
            ->get();
    }

    public static function getListReceiptsYear(int $categoryId): mixed
    {
        return Payment::join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
            ->join('rates', 'payments.rate_id', 'rates.id')
            ->select(
                'category_fees.name as category_name',
                DB::raw('payments.month as month'),
                DB::raw('SUM(scolar_fees.amount) as total_amount'),
                'category_fees.currency as currency_name'
            )
            ->where('category_fees.id', $categoryId) // Remplacez 1 par l'ID de la catégorie souhaitée
            ->groupBy('category_fees.name', 'payments.month', 'category_fees.currency')
            ->where('payments.is_paid', true)
            ->get();
    }

    public static function getPaymentsByMonthAndCategory(int $categoryId): mixed
    {
        return self::join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->select(
                DB::raw('payments.month as month'),
                'category_fees.name as category_name',
                DB::raw('SUM(scolar_fees.amount) as total_amount')
            )
            ->where('payments.is_paid', true)
            ->where('category_fees.id', $categoryId)
            ->groupBy(DB::raw('payments.month'), 'category_fees.name')
            ->get();
    }
}
