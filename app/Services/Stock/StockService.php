<?php

namespace App\Services\Stock;

use App\Models\Article;
use App\Models\ArticleStockMovement;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Récupère tous les articles ordonnés par nom
     */
    public function getAllArticles(): Collection
    {
        return Article::with('stockMovements')
            ->orderBy('name')
            ->get();
    }

    /**
     * Crée un nouvel article
     */
    public function createArticle(array $data): Article
    {
        return Article::create([
            'name' => $data['name'],
            'reference' => $data['reference'] ?? null,
            'unit' => $data['unit'] ?? null,
            'description' => $data['description'] ?? null,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
        ]);
    }

    /**
     * Met à jour un article existant
     */
    public function updateArticle(int $articleId, array $data): Article
    {
        $article = Article::findOrFail($articleId);

        $article->update([
            'name' => $data['name'],
            'reference' => $data['reference'] ?? null,
            'unit' => $data['unit'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return $article->fresh();
    }

    /**
     * Supprime un article
     *
     * @throws \Exception si l'article a des mouvements clôturés
     */
    public function deleteArticle(int $articleId): void
    {
        $article = Article::findOrFail($articleId);

        // Vérifier s'il y a des mouvements clôturés
        if ($article->stockMovements()->where('is_closed', true)->exists()) {
            throw new \Exception('Impossible de supprimer un article avec des mouvements clôturés.');
        }

        $article->delete();
    }

    /**
     * Récupère un article avec ses mouvements de stock
     */
    public function getArticleWithMovements(int $articleId): Article
    {
        return Article::with(['stockMovements' => function ($query) {
            $query->orderByDesc('movement_date');
        }])->findOrFail($articleId);
    }

    /**
     * Ajoute un mouvement de stock
     *
     * @throws \Exception si le stock est insuffisant pour une sortie
     */
    public function addStockMovement(array $data): ArticleStockMovement
    {
        return DB::transaction(function () use ($data) {
            $article = Article::findOrFail($data['article_id']);

            // Validation du stock pour les sorties
            if ($data['type'] === 'out') {
                $availableStock = $article->stock;

                if ($data['quantity'] > $availableStock) {
                    throw new \Exception(
                        "Stock insuffisant pour cette sortie. Stock disponible : {$availableStock}"
                    );
                }
            }

            return ArticleStockMovement::create([
                'article_id' => $data['article_id'],
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'movement_date' => $data['movement_date'],
                'note' => $data['note'] ?? null,
                'user_id' => Auth::id(),
                'school_id' => School::DEFAULT_SCHOOL_ID(),
                'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
                'is_closed' => false,
            ]);
        });
    }

    /**
     * Met à jour un mouvement de stock
     *
     * @throws \Exception si le mouvement est clôturé ou le stock insuffisant
     */
    public function updateStockMovement(int $movementId, array $data): ArticleStockMovement
    {
        return DB::transaction(function () use ($movementId, $data) {
            $movement = ArticleStockMovement::findOrFail($movementId);

            if ($movement->is_closed) {
                throw new \Exception('Impossible de modifier un mouvement clôturé.');
            }

            $article = $movement->article;

            // Validation du stock pour les sorties
            if ($data['type'] === 'out') {
                // Calculer le stock disponible en annulant temporairement l'ancien mouvement
                $currentStock = $article->stock;
                if ($movement->type === 'out') {
                    $currentStock += $movement->quantity;
                }

                if ($data['quantity'] > $currentStock) {
                    throw new \Exception(
                        "Stock insuffisant pour cette modification. Stock disponible : {$currentStock}"
                    );
                }
            }

            $movement->update([
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'movement_date' => $data['movement_date'],
                'note' => $data['note'] ?? null,
            ]);

            return $movement->fresh();
        });
    }

    /**
     * Clôture un mouvement de stock
     */
    public function closeStockMovement(int $movementId): ArticleStockMovement
    {
        $movement = ArticleStockMovement::findOrFail($movementId);
        $movement->update([
            'is_closed' => true,
            'closed_date' => now()->toDateString(),
        ]);

        return $movement;
    }

    /**
     * Calcule les statistiques de stock
     */
    public function getStockStatistics(): array
    {
        $articles = $this->getAllArticles();

        return [
            'total_articles' => $articles->count(),
            'articles_in_stock' => $articles->filter(fn ($a) => $a->stock > 0)->count(),
            'articles_out_of_stock' => $articles->filter(fn ($a) => $a->stock <= 0)->count(),
            'total_stock_value' => $articles->sum('stock'),
        ];
    }
}
