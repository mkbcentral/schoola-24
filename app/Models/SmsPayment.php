<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsPayment extends Model
{

    use HasFactory;

    protected $fillable = ['receiver', 'message', 'payment_id'];

    /**
     * Get the payment that owns the SmsPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
