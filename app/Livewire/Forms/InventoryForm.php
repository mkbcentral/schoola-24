<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use App\Models\ArticleInventory;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InventoryForm extends Form
{
    public ?ArticleInventory $inventory = null;

    #[Validate('required|exists:articles,id')]
    public $article_id = '';

    #[Validate('required|integer|min:0')]
    public $expected_quantity = 0;

    #[Validate('required|integer|min:0')]
    public $actual_quantity = 0;

    public $difference = 0;

    public $status = '';

    #[Validate('required|date')]
    public $inventory_date = '';

    #[Validate('nullable|string|max:500')]
    public $note = '';

    /**
     * Initialiser le formulaire avec les données de l'article
     */
    public function setArticle(Article $article)
    {
        $this->article_id = $article->id;
        $this->expected_quantity = $article->stock;
        $this->actual_quantity = 0;
        $this->inventory_date = now()->format('Y-m-d');
        $this->calculateDifference();
    }

    /**
     * Calculer automatiquement la différence et le statut
     */
    public function calculateDifference()
    {
        $this->difference = (int) $this->actual_quantity - (int) $this->expected_quantity;

        if ($this->difference == 0) {
            $this->status = 'conforme';
        } elseif ($this->difference > 0) {
            $this->status = 'excedent';
        } else {
            $this->status = 'manquant';
        }
    }

    /**
     * Créer un nouvel inventaire
     */
    public function create()
    {
        $this->validate();
        $this->calculateDifference();

        return ArticleInventory::create([
            'article_id' => $this->article_id,
            'expected_quantity' => $this->expected_quantity,
            'actual_quantity' => $this->actual_quantity,
            'difference' => $this->difference,
            'status' => $this->status,
            'inventory_date' => $this->inventory_date,
            'note' => $this->note,
            'user_id' => Auth::id(),
            'school_id' => School::DEFAULT_SCHOOL_ID(),
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
        ]);
    }

    /**
     * Charger les données d'un inventaire existant
     */
    public function setInventory(ArticleInventory $inventory)
    {
        $this->inventory = $inventory;
        $this->article_id = $inventory->article_id;
        $this->expected_quantity = $inventory->expected_quantity;
        $this->actual_quantity = $inventory->actual_quantity;
        $this->difference = $inventory->difference;
        $this->status = $inventory->status;
        $this->inventory_date = $inventory->inventory_date instanceof \Carbon\Carbon
            ? $inventory->inventory_date->format('Y-m-d')
            : $inventory->inventory_date;
        $this->note = $inventory->note;
    }

    /**
     * Mettre à jour un inventaire existant
     */
    public function update()
    {
        $this->validate();
        $this->calculateDifference();

        $this->inventory->update([
            'expected_quantity' => $this->expected_quantity,
            'actual_quantity' => $this->actual_quantity,
            'difference' => $this->difference,
            'status' => $this->status,
            'inventory_date' => $this->inventory_date,
            'note' => $this->note,
        ]);

        return $this->inventory;
    }

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties)
    {
        $this->inventory = null;
        $this->article_id = '';
        $this->expected_quantity = 0;
        $this->actual_quantity = 0;
        $this->difference = 0;
        $this->status = '';
        $this->inventory_date = '';
        $this->note = '';
    }
}
