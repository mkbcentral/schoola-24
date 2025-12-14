<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use App\Models\ArticleStockMovement;
use App\Models\School;
use App\Models\SchoolYear;
use Auth;
use Livewire\Attributes\Rule;
use Livewire\Form;

class StockMovementForm extends Form
{
    public ?ArticleStockMovement $movement = null;

    public ?Article $article = null;

    #[Rule('required|in:in,out', message: 'Le type de mouvement est obligatoire', onUpdate: false)]
    public $type = 'in';

    #[Rule('required|integer|min:1', message: 'La quantité doit être au moins de 1', onUpdate: false)]
    public $quantity = 0;

    #[Rule('required|date|before_or_equal:today', message: 'La date est obligatoire et ne peut pas être dans le futur', onUpdate: false)]
    public $movement_date = '';

    #[Rule('nullable|string|max:500', message: 'La note ne doit pas dépasser 500 caractères', onUpdate: false)]
    public $note = '';

    /**
     * Définir l'article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * Définir le mouvement pour l'édition
     */
    public function setMovement(ArticleStockMovement $movement): void
    {
        $this->movement = $movement;
        $this->type = $movement->type;
        $this->quantity = $movement->quantity;
        $this->movement_date = $movement->movement_date;
        $this->note = $movement->note;
    }

    /**
     * Valider le stock disponible pour une sortie
     */
    public function validateStock(): bool
    {
        if ($this->type !== 'out' || ! $this->article) {
            return true;
        }

        $stock = $this->article->stock;

        // Si on édite un mouvement, on ajoute son ancienne quantité au stock
        if ($this->movement && $this->movement->type === 'out' && ! $this->movement->is_closed) {
            $stock += $this->movement->quantity;
        }

        return $this->quantity <= $stock;
    }

    /**
     * Obtenir le stock disponible
     */
    public function getAvailableStock(): int
    {
        if (! $this->article) {
            return 0;
        }

        $stock = $this->article->stock;

        // Si on édite un mouvement de sortie non clôturé, on ajoute son ancienne quantité
        if ($this->movement && $this->movement->type === 'out' && ! $this->movement->is_closed) {
            $stock += $this->movement->quantity;
        }

        return $stock;
    }

    /**
     * Créer un nouveau mouvement de stock
     */
    public function create(): ArticleStockMovement
    {
        $this->validate();

        if (! $this->validateStock()) {
            throw new \Exception('Stock insuffisant pour cette sortie. Stock disponible : ' . $this->getAvailableStock());
        }

        return ArticleStockMovement::create([
            'article_id' => $this->article->id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'movement_date' => $this->movement_date,
            'user_id' => Auth::id(),
            'school_id' => School::DEFAULT_SCHOOL_ID(),
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
            'note' => $this->note,
        ]);
    }

    /**
     * Mettre à jour un mouvement existant
     */
    public function update(): void
    {
        $this->validate();

        if ($this->movement->is_closed) {
            throw new \Exception('Impossible de modifier un mouvement clôturé.');
        }

        if (! $this->validateStock()) {
            throw new \Exception('Stock insuffisant pour cette sortie. Stock disponible : ' . $this->getAvailableStock());
        }

        $this->movement->update([
            'type' => $this->type,
            'quantity' => $this->quantity,
            'movement_date' => $this->movement_date,
            'note' => $this->note,
        ]);
    }

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        $this->movement = null;
        $this->type = 'in';
        $this->quantity = 0;
        $this->movement_date = date('Y-m-d');
        $this->note = '';

        parent::reset(...$properties);
    }
}
