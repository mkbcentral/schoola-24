<?php

namespace App\Livewire\Application\Stock;

use App\Livewire\Forms\InventoryForm;
use App\Models\Article;
use App\Models\ArticleInventory;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleInventoryManager extends Component
{
    use WithPagination;

    public InventoryForm $form;

    public $selectedArticle = null;

    public $showModal = false;

    public $isEditing = false;

    // Filtres
    public $search = '';

    public $statusFilter = '';

    public $dateFrom = '';

    public $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    /**
     * Ouvrir le modal pour créer un inventaire
     */
    public function openCreateModal(?int $articleId = null)
    {
        $this->form->reset();
        $this->isEditing = false;

        if ($articleId) {
            $article = Article::findOrFail($articleId);
            $this->form->setArticle($article);
            $this->selectedArticle = $article;
        }

        $this->showModal = true;
    }

    /**
     * Créer un inventaire
     */
    public function createInventory()
    {
        try {
            $inventory = $this->form->create();

            // Ajuster le stock de l'article si nécessaire
            $article = Article::find($inventory->article_id);
            if ($article && $inventory->difference != 0) {
                $article->update([
                    'stock' => $inventory->actual_quantity,
                ]);
            }

            $this->dispatch('inventoryCreated');
            $this->showModal = false;
            $this->form->reset();
            $this->selectedArticle = null;

            session()->flash('success', 'Inventaire enregistré avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Ouvrir le modal pour éditer un inventaire
     */
    public function editInventory(int $inventoryId)
    {
        $inventory = ArticleInventory::with('article')->findOrFail($inventoryId);
        $this->form->setInventory($inventory);
        $this->selectedArticle = $inventory->article;
        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Mettre à jour un inventaire
     */
    public function updateInventory()
    {
        try {
            $inventory = $this->form->update();

            // Ajuster le stock de l'article si nécessaire
            $article = Article::find($inventory->article_id);
            if ($article) {
                $article->update([
                    'stock' => $inventory->actual_quantity,
                ]);
            }

            $this->dispatch('inventoryUpdated');
            $this->showModal = false;
            $this->form->reset();
            $this->selectedArticle = null;

            session()->flash('success', 'Inventaire mis à jour avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un inventaire
     */
    public function deleteInventory(int $inventoryId)
    {
        try {
            $inventory = ArticleInventory::findOrFail($inventoryId);
            $inventory->delete();

            $this->dispatch('inventoryDeleted');
            session()->flash('success', 'Inventaire supprimé avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Annuler et fermer le modal
     */
    public function cancel()
    {
        $this->showModal = false;
        $this->form->reset();
        $this->selectedArticle = null;
        $this->isEditing = false;
    }

    /**
     * Recalculer la différence lors de la modification
     */
    public function updated($property)
    {
        if (in_array($property, ['form.actual_quantity', 'form.expected_quantity'])) {
            $this->form->calculateDifference();
        }
    }

    /**
     * Rafraîchir la liste
     */
    #[On('inventoryCreated')]
    #[On('inventoryUpdated')]
    #[On('inventoryDeleted')]
    public function refreshList()
    {
        $this->resetPage();
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $inventoriesQuery = ArticleInventory::with(['article.category', 'user'])
            ->orderBy('inventory_date', 'desc');

        // Filtre par recherche
        if ($this->search) {
            $inventoriesQuery->whereHas('article', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('reference', 'like', '%' . $this->search . '%');
            });
        }

        // Filtre par statut
        if ($this->statusFilter) {
            $inventoriesQuery->where('status', $this->statusFilter);
        }

        // Filtre par date
        if ($this->dateFrom) {
            $inventoriesQuery->where('inventory_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $inventoriesQuery->where('inventory_date', '<=', $this->dateTo);
        }

        $inventories = $inventoriesQuery->paginate(15);

        // Statistiques
        $stats = [
            'total' => ArticleInventory::count(),
            'conforme' => ArticleInventory::where('status', 'conforme')->count(),
            'excedent' => ArticleInventory::where('status', 'excedent')->count(),
            'manquant' => ArticleInventory::where('status', 'manquant')->count(),
        ];

        return view('livewire.application.stock.article-inventory-manager', [
            'inventories' => $inventories,
            'articles' => Article::orderBy('name')->get(),
            'stats' => $stats,
        ]);
    }
}
