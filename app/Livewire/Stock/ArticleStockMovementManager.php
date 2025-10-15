<?php

namespace App\Livewire\Stock;

use App\Models\Article;
use App\Models\ArticleStockMovement;
use App\Models\School;
use App\Models\SchoolYear;
use Auth;
use Livewire\Component;

class ArticleStockMovementManager extends Component
{
    public $selectedArticle;
    public $selectedArticleName = '';
    public $stockMovements = [];
    public $movement_type = 'in';
    public $movement_quantity = 0;
    public $movement_date;
    public $movement_note = '';
    public $editMovementMode = false;
    public $movement_id = null;

    public function mount($articleId = null)
    {
        if ($articleId) {
            $this->selectedArticle = Article::findOrFail($articleId);
            $this->selectedArticleName = $this->selectedArticle->name;
            $this->stockMovements = $this->selectedArticle->stockMovements()->orderByDesc('movement_date')->get();
        }
        $this->movement_date = date('Y-m-d');
    }

    public function showStockMovements($id)
    {
        $this->selectedArticle = Article::findOrFail($id);
        $this->selectedArticleName = $this->selectedArticle->name;
        $this->stockMovements = $this->selectedArticle->stockMovements()->orderByDesc('movement_date')->get();
    }

    public function editStockMovement($id)
    {
        $movement = ArticleStockMovement::findOrFail($id);
        $this->movement_id = $movement->id;
        $this->movement_type = $movement->type;
        $this->movement_quantity = $movement->quantity;
        $this->movement_date = $movement->movement_date;
        $this->movement_note = $movement->note;
        $this->editMovementMode = true;
    }

    public function addStockMovement()
    {
        $this->validate([
            'selectedArticle' => 'required',
            'movement_type' => 'required|in:in,out',
            'movement_quantity' => 'required|integer|min:1',
            'movement_date' => 'required|date',
        ]);
        if ($this->movement_type === 'out') {
            $stock = $this->selectedArticle->stock;
            if ($this->editMovementMode && $this->movement_id) {
                $oldMove = ArticleStockMovement::find($this->movement_id);
                if ($oldMove && $oldMove->type === 'out' && !$oldMove->is_closed) {
                    $stock += $oldMove->quantity;
                }
            }
            if ($this->movement_quantity > $stock) {
                $this->addError('movement_quantity', 'Stock insuffisant pour cette sortie. Stock disponible : ' . $stock);
                $this->dispatch('error', ['message' => 'Stock insuffisant pour cette sortie. Stock disponible : ' . $stock]);
                return;
            }
        }
        if ($this->editMovementMode && $this->movement_id) {
            $movement = ArticleStockMovement::findOrFail($this->movement_id);
            if ($movement->is_closed) {
                $this->dispatch('error', ['message' => 'Impossible de modifier un mouvement clôturé.']);
                return;
            }
            $movement->update([
                'type' => $this->movement_type,
                'quantity' => $this->movement_quantity,
                'movement_date' => $this->movement_date,
                'note' => $this->movement_note,
            ]);
        } else {
            ArticleStockMovement::create([
                'article_id' => $this->selectedArticle->id,
                'type' => $this->movement_type,
                'quantity' => $this->movement_quantity,
                'movement_date' => $this->movement_date,
                'user_id' => Auth::id(),
                'school_id' => School::DEFAULT_SCHOOL_ID(),
                'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
                'note' => $this->movement_note,
            ]);
        }
        $this->showStockMovements($this->selectedArticle->id);
        $this->resetMovementForm();
    }

    public function closeStockMovement($id)
    {
        $movement = ArticleStockMovement::findOrFail($id);
        $movement->update(['is_closed' => true]);
        $this->showStockMovements($movement->article_id);
        $this->resetMovementForm();
    }

    public function resetMovementForm()
    {
        $this->movement_id = null;
        $this->movement_type = 'in';
        $this->movement_quantity = 0;
        $this->movement_date = null;
        $this->movement_note = '';
        $this->editMovementMode = false;
    }

    public function render()
    {
        return view('livewire.stock.article-stock-movement-manager');
    }
}
