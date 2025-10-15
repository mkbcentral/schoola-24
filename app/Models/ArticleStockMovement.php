<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleStockMovement extends Model
{
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
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
