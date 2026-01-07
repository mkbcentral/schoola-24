<?php

namespace App\Livewire\Inventory;

use App\Livewire\Forms\StockMovementForm;
use App\Models\Article;
use App\Models\ArticleStockMovement;
use App\Services\Stock\StockService;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleStockMovementManager extends Component
{
    use WithPagination;

    public StockMovementForm $form;

    public $selectedArticle;

    public $selectedArticleName = '';

    public $editMovementMode = false;

    // Filtres
    public $filterType = 'all'; // all, in, out

    public $filterPeriod = 'all'; // all, today, week, month, custom

    public $filterDateStart = '';

    public $filterDateEnd = '';

    protected $stockService;

    public function boot(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function mount(Article $article)
    {
        $this->selectedArticle = $article;
        $this->selectedArticleName = $article->name;
        $this->form->setArticle($article);
        $this->form->movement_date = date('Y-m-d');
    }

    /**
     * Afficher les mouvements d'un article
     */
    public function showStockMovements($id)
    {
        try {
            $this->selectedArticle = Article::findOrFail($id);
            $this->selectedArticleName = $this->selectedArticle->name;
            $this->form->setArticle($this->selectedArticle);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Préparer l'édition d'un mouvement
     */
    public function editStockMovement($id)
    {
        try {
            $movement = ArticleStockMovement::findOrFail($id);

            if ($movement->is_closed) {
                $this->dispatch('error', ['message' => 'Impossible de modifier un mouvement clôturé.']);

                return;
            }

            $this->form->setMovement($movement);
            $this->editMovementMode = true;
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Ajouter ou mettre à jour un mouvement
     */
    public function addStockMovement()
    {
        try {
            if ($this->editMovementMode && $this->form->movement) {
                $this->form->update();
                $this->dispatch('added', ['message' => 'Mouvement modifié avec succès !']);
            } else {
                $this->form->create();
                $this->dispatch('added', ['message' => 'Mouvement ajouté avec succès !']);
            }

            $this->resetMovementForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Clôturer un mouvement
     */
    public function closeStockMovement($id)
    {
        try {
            $this->stockService->closeStockMovement($id);

            $this->dispatch('added', ['message' => 'Mouvement clôturé avec succès !']);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Réinitialiser le formulaire de mouvement
     */
    public function resetMovementForm()
    {
        $this->form->reset();
        $this->form->setArticle($this->selectedArticle);
        $this->form->movement_date = date('Y-m-d');
        $this->editMovementMode = false;
    }

    /**
     * Exporter les mouvements en PDF
     */
    public function exportPdf()
    {
        if (! $this->selectedArticle) {
            $this->dispatch('error', ['message' => 'Aucun article sélectionné']);

            return;
        }

        // Construire l'URL avec les paramètres de filtre
        $params = [
            'filterType' => $this->filterType,
            'filterPeriod' => $this->filterPeriod,
            'filterDateStart' => $this->filterDateStart,
            'filterDateEnd' => $this->filterDateEnd,
        ];

        return redirect()->route('stock.export.movements.pdf', [
            'article' => $this->selectedArticle->id,
            ...$params,
        ]);
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters()
    {
        $this->filterType = 'all';
        $this->filterPeriod = 'all';
        $this->filterDateStart = '';
        $this->filterDateEnd = '';
        $this->resetPage();
    }

    /**
     * Mise à jour des filtres - reset pagination
     */
    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterPeriod()
    {
        $this->resetPage();
    }

    public function updatingFilterDateStart()
    {
        $this->resetPage();
    }

    public function updatingFilterDateEnd()
    {
        $this->resetPage();
    }

    /**
     * Obtenir les mouvements avec pagination et filtres
     */
    public function getStockMovementsProperty()
    {
        if (! $this->selectedArticle) {
            return collect();
        }

        $query = $this->selectedArticle->stockMovements();

        // Filtre par type
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        // Filtre par période
        switch ($this->filterPeriod) {
            case 'today':
                $query->whereDate('movement_date', today());
                break;
            case 'week':
                $query->whereBetween('movement_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
                break;
            case 'month':
                $query->whereYear('movement_date', now()->year)
                    ->whereMonth('movement_date', now()->month);
                break;
            case 'custom':
                if ($this->filterDateStart) {
                    $query->whereDate('movement_date', '>=', $this->filterDateStart);
                }
                if ($this->filterDateEnd) {
                    $query->whereDate('movement_date', '<=', $this->filterDateEnd);
                }
                break;
        }

        return $query
            ->orderByDesc('movement_date')
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    /**
     * Obtenir les statistiques filtrées
     */
    public function getFilteredStatsProperty()
    {
        if (! $this->selectedArticle) {
            return ['in' => 0, 'out' => 0, 'count' => 0];
        }

        $query = $this->selectedArticle->stockMovements()->where('is_closed', true);

        // Appliquer les mêmes filtres
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        switch ($this->filterPeriod) {
            case 'today':
                $query->whereDate('movement_date', today());
                break;
            case 'week':
                $query->whereBetween('movement_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
                break;
            case 'month':
                $query->whereYear('movement_date', now()->year)
                    ->whereMonth('movement_date', now()->month);
                break;
            case 'custom':
                if ($this->filterDateStart) {
                    $query->whereDate('movement_date', '>=', $this->filterDateStart);
                }
                if ($this->filterDateEnd) {
                    $query->whereDate('movement_date', '<=', $this->filterDateEnd);
                }
                break;
        }

        $movements = $query->get();

        return [
            'in' => $movements->where('type', 'in')->sum('quantity'),
            'out' => $movements->where('type', 'out')->sum('quantity'),
            'count' => $movements->count(),
        ];
    }

    public function render()
    {
        return view('livewire.application.stock.article-stock-movement-manager', [
            'stockMovements' => $this->stockMovements,
            'filteredStats' => $this->filteredStats,
        ]);
    }
}
