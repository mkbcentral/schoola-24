<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver',
        'message',
        'payment_id',
        'resource_id',
        'status',
        'delivery_status',
        'sent_at',
        'delivered_at',
        'error_message'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the payment that owns the SmsPayment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
