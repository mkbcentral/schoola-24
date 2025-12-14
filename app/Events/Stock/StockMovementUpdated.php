<?php

namespace App\Events\Stock;

use App\Models\ArticleStockMovement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockMovementUpdated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public ArticleStockMovement $movement;

    /**
     * Create a new event instance.
     */
    public function __construct(ArticleStockMovement $movement)
    {
        $this->movement = $movement;
    }
}
