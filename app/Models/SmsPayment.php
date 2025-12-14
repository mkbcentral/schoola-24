<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsPayment extends Model
{
    use HasFactory;

    protected $fillable = ['receiver', 'message', 'payment_id'];

    /**
     * Get the payment that owns the SmsPayment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
