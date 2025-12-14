<?php

namespace App\Models;

use App\Traits\StockMovementAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleStockMovement extends Model
{
    use StockMovementAuditable;

    protected $fillable = [
        'article_id',
        'type',
        'quantity',
        'movement_date',
        'user_id',
        'school_id',
        'school_year_id',
        'note',
        'is_closed',
        'closed_date',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
