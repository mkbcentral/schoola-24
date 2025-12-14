<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the registration that owns the Payment
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Get the scolarFee that owns the Payment
     */
    public function scolarFee(): BelongsTo
    {
        return $this->belongsTo(ScolarFee::class, 'scolar_fee_id');
    }

    /**
     * Get the rate that owns the Payment
     */
    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    /**
     * Get the user that owns the Payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the SmsPayment associated with the Payment
     */
    public function smsPayment(): HasOne
    {
        return $this->hasOne(SmsPayment::class);
    }

    /**
     * Retourner le montant du paiement
     */
    public function getAmount(): int|float
    {
        return $this->scolarFee->amount;
    }

    /**
     * Vérifier si le paiement est payé
     */
    public function isPaid(): bool
    {
        return $this->is_paid;
    }

    /**
     * Obtenir le nom de l'élève
     */
    public function getStudentName(): string
    {
        return $this->registration->student->name ?? '';
    }

    /**
     * Obtenir le nom de la catégorie de frais
     */
    public function getCategoryName(): string
    {
        return $this->scolarFee->categoryFee->name ?? '';
    }

    /**
     * @deprecated Utiliser PaymentRepository::getTotalAmountByCategory()
     * Cette méthode sera supprimée dans une future version.
     * Migrez vers: app(PaymentRepositoryInterface::class)->getTotalAmountByCategory($month, $date)
     */
    public static function getTotalAmountByCategoryForMonthOrDate(?string $month, ?string $date)
    {
        trigger_error(
            'getTotalAmountByCategoryForMonthOrDate() est déprécié. Utilisez PaymentRepository::getTotalAmountByCategory()',
            E_USER_DEPRECATED
        );

        return app(\App\Repositories\Contracts\PaymentRepositoryInterface::class)
            ->getTotalAmountByCategory($month, $date);
    }

    /**
     * @deprecated Utiliser PaymentRepository::getYearlyReceiptsByCategory()
     * Cette méthode sera supprimée dans une future version.
     * Migrez vers: app(PaymentRepositoryInterface::class)->getYearlyReceiptsByCategory($categoryId)
     */
    public static function getListReceiptsYear(int $categoryId): mixed
    {
        trigger_error(
            'getListReceiptsYear() est déprécié. Utilisez PaymentRepository::getYearlyReceiptsByCategory()',
            E_USER_DEPRECATED
        );

        return app(\App\Repositories\Contracts\PaymentRepositoryInterface::class)
            ->getYearlyReceiptsByCategory($categoryId);
    }

    /**
     * @deprecated Utiliser PaymentRepository::getPaymentsByMonthAndCategory()
     * Cette méthode sera supprimée dans une future version.
     * Migrez vers: app(PaymentRepositoryInterface::class)->getPaymentsByMonthAndCategory($categoryId)
     */
    public static function getPaymentsByMonthAndCategory(int $categoryId): mixed
    {
        trigger_error(
            'getPaymentsByMonthAndCategory() est déprécié. Utilisez PaymentRepository::getPaymentsByMonthAndCategory()',
            E_USER_DEPRECATED
        );

        return app(\App\Repositories\Contracts\PaymentRepositoryInterface::class)
            ->getPaymentsByMonthAndCategory($categoryId);
    }
}
