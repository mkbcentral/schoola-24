<?php

namespace App\Livewire\Application\Stock;

use App\Livewire\Forms\ArticleForm;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\Stock\ArticleExportService;
use App\Services\Stock\StockService;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleStockManager extends Component
{
    use WithPagination;

    public ArticleForm $form;

    public $search = '';

    public $editMode = false;

    public $selectedCategory = '';

    protected $stockService;

    public function boot(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function mount()
    {
        // Initialisation du formulaire
    }

    /**
     * Créer un nouvel article
     */
    public function createArticle()
    {
        $article = $this->form->create();

        $this->dispatch('added', ['message' => 'Article créé avec succès !']);
        $this->form->reset();
        $this->resetPage();
    }

    /**
     * Préparer l'édition d'un article
     */
    public function editArticle($id)
    {
        try {
            $article = Article::findOrFail($id);
            $this->form->setArticle($article);
            $this->editMode = true;
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Mettre à jour un article
     */
    public function updateArticle()
    {
        try {
            $this->form->update();

            $this->dispatch('added', ['message' => 'Article modifié avec succès !']);
            $this->resetForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer un article
     */
    public function deleteArticle($id)
    {
        try {
            $this->stockService->deleteArticle($id);

            $this->dispatch('added', ['message' => 'Article supprimé avec succès !']);
            $this->resetForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher les mouvements de stock d'un article
     */
    public function showStockMovements($id)
    {
        return redirect()->route('app.stock.movements', ['article' => $id]);
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->form->reset();
        $this->editMode = false;
    }

    /**
     * Exporter les articles en CSV
     */
    public function exportArticles()
    {
        try {
            $exportService = new ArticleExportService;

            return $exportService->exportAllArticles($this->search);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur lors de l\'export : ' . $e->getMessage()]);
        }
    }

    /**
     * Mise à jour de la recherche
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Mise à jour du filtre de catégorie
     */
    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    /**
     * Obtenir les articles avec pagination
     */
    public function getArticlesProperty()
    {
        return Article::query()
            ->with('category')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('reference', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory, function ($query) {
                if ($this->selectedCategory === 'none') {
                    $query->whereNull('category_id');
                } else {
                    $query->where('category_id', $this->selectedCategory);
                }
            })
            ->orderBy('name')
            ->paginate(10);
    }

    /**
     * Obtenir toutes les catégories
     */
    public function getCategoriesProperty()
    {
        return ArticleCategory::orderBy('name')->get();
    }

    /**
     * Obtenir les articles avec stock faible
     */
    public function getLowStockArticlesProperty()
    {
        return Article::whereNotNull('stock_min')
            ->where('stock_min', '>', 0)
            ->get()
            ->filter(function ($article) {
                return $article->stock <= $article->stock_min;
            })
            ->sortBy('stock');
    }

    public function render()
    {
        return view('livewire.application.stock.article-stock-manager', [
            'articles' => $this->articles,
            'lowStockArticles' => $this->lowStockArticles,
            'categories' => $this->categories,
        ]);
    }
}
