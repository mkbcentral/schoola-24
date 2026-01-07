<?php

namespace App\Livewire\Inventory;

use App\Models\Article;
use App\Models\ArticleStockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockDashboard extends Component
{
    /**
     * Obtenir le nombre total d'articles
     */
    public function getTotalArticlesProperty()
    {
        return Article::count();
    }

    /**
     * Obtenir le stock total de tous les articles
     */
    public function getTotalStockProperty()
    {
        return Article::all()->sum(function ($article) {
            return $article->stock;
        });
    }

    /**
     * Obtenir le nombre de mouvements du mois en cours
     */
    public function getMonthlyMovementsProperty()
    {
        return ArticleStockMovement::whereYear('movement_date', Carbon::now()->year)
            ->whereMonth('movement_date', Carbon::now()->month)
            ->where('is_closed', true)
            ->count();
    }

    /**
     * Obtenir le nombre d'articles en alerte
     */
    public function getLowStockCountProperty()
    {
        return Article::whereNotNull('stock_min')
            ->where('stock_min', '>', 0)
            ->get()
            ->filter(function ($article) {
                return $article->stock <= $article->stock_min;
            })
            ->count();
    }

    /**
     * Obtenir les articles en alerte de stock
     */
    public function getLowStockArticlesProperty()
    {
        return Article::with('category')
            ->whereNotNull('stock_min')
            ->where('stock_min', '>', 0)
            ->get()
            ->filter(function ($article) {
                return $article->stock <= $article->stock_min;
            })
            ->sortBy('stock')
            ->take(10);
    }

    /**
     * Obtenir les données pour le graphique des mouvements (Entrées vs Sorties)
     */
    public function getMovementsByTypeProperty()
    {
        // Tous les mouvements clôturés (pas seulement ce mois)
        $entries = ArticleStockMovement::where('type', 'in')
            ->where('is_closed', true)
            ->sum('quantity');

        $exits = ArticleStockMovement::where('type', 'out')
            ->where('is_closed', true)
            ->sum('quantity');

        return [
            'entries' => $entries ?? 0,
            'exits' => $exits ?? 0,
        ];
    }

    /**
     * Obtenir l'évolution du stock sur les 6 derniers mois
     */
    public function getStockEvolutionProperty()
    {
        $months = [];
        $stockData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('fr')->isoFormat('MMM YYYY');

            // Calculer le stock cumulé jusqu'à cette date
            $entries = ArticleStockMovement::where('type', 'in')
                ->where('is_closed', true)
                ->where('movement_date', '<=', $date->endOfMonth())
                ->sum('quantity');

            $exits = ArticleStockMovement::where('type', 'out')
                ->where('is_closed', true)
                ->where('movement_date', '<=', $date->endOfMonth())
                ->sum('quantity');

            $stockData[] = $entries - $exits;
        }

        return [
            'labels' => $months,
            'data' => $stockData,
        ];
    }

    /**
     * Obtenir les 10 articles les plus utilisés
     */
    public function getTopArticlesProperty()
    {
        return Article::with('category')
            ->withCount(['stockMovements' => function ($query) {
                $query->where('is_closed', true);
            }])
            ->having('stock_movements_count', '>', 0)
            ->orderBy('stock_movements_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Obtenir les mouvements récents
     */
    public function getRecentMovementsProperty()
    {
        return ArticleStockMovement::with(['article', 'user'])
            ->where('is_closed', true)
            ->orderBy('closed_date', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Obtenir les statistiques par catégorie
     */
    public function getCategoryStatsProperty()
    {
        return DB::table('articles')
            ->leftJoin('article_categories', 'articles.category_id', '=', 'article_categories.id')
            ->select(
                'article_categories.name as category_name',
                'article_categories.color',
                DB::raw('COUNT(articles.id) as total_articles')
            )
            ->groupBy('article_categories.id', 'article_categories.name', 'article_categories.color')
            ->orderBy('total_articles', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.application.stock.stock-dashboard', [
            'totalArticles' => $this->totalArticles,
            'totalStock' => $this->totalStock,
            'monthlyMovements' => $this->monthlyMovements,
            'lowStockCount' => $this->lowStockCount,
            'lowStockArticles' => $this->lowStockArticles,
            'movementsByType' => $this->movementsByType,
            'stockEvolution' => $this->stockEvolution,
            'topArticles' => $this->topArticles,
            'recentMovements' => $this->recentMovements,
            'categoryStats' => $this->categoryStats,
        ]);
    }
}
