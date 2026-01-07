<?php

namespace App\Livewire\Inventory;

use App\Models\Article;
use App\Models\ArticleAudit;
use Livewire\Component;
use Livewire\WithPagination;

class AuditHistoryViewer extends Component
{
    use WithPagination;

    public ?Article $article = null;

    public $articleId = null;

    // Filtres
    public $actionFilter = '';

    public $userFilter = '';

    public $dateFrom = '';

    public $dateTo = '';

    protected $queryString = [
        'actionFilter' => ['except' => ''],
        'articleId' => ['except' => null],
    ];

    public function mount(?int $articleId = null)
    {
        if ($articleId) {
            $this->articleId = $articleId;
            $this->article = Article::find($articleId);
        }
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters()
    {
        $this->actionFilter = '';
        $this->userFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    /**
     * Voir les détails d'un article
     */
    public function viewArticle(?int $articleId = null)
    {
        $this->articleId = $articleId;
        $this->article = $articleId ? Article::find($articleId) : null;
        $this->resetPage();
    }

    public function render()
    {
        $auditsQuery = ArticleAudit::with(['article', 'user'])
            ->orderBy('created_at', 'desc');

        // Filtre par article spécifique
        if ($this->articleId) {
            $auditsQuery->where('article_id', $this->articleId);
        }

        // Filtre par action
        if ($this->actionFilter) {
            $auditsQuery->where('action', $this->actionFilter);
        }

        // Filtre par utilisateur
        if ($this->userFilter) {
            $auditsQuery->where('user_id', $this->userFilter);
        }

        // Filtre par date
        if ($this->dateFrom) {
            $auditsQuery->where('created_at', '>=', $this->dateFrom . ' 00:00:00');
        }
        if ($this->dateTo) {
            $auditsQuery->where('created_at', '<=', $this->dateTo . ' 23:59:59');
        }

        $audits = $auditsQuery->paginate(20);

        // Statistiques globales
        $stats = [
            'total' => ArticleAudit::count(),
            'created' => ArticleAudit::where('action', 'created')->count(),
            'updated' => ArticleAudit::where('action', 'updated')->count(),
            'deleted' => ArticleAudit::where('action', 'deleted')->count(),
            'stock_adjusted' => ArticleAudit::where('action', 'stock_adjusted')->count(),
            'movement_created' => ArticleAudit::where('action', 'movement_created')->count(),
            'movement_updated' => ArticleAudit::where('action', 'movement_updated')->count(),
            'movement_closed' => ArticleAudit::where('action', 'movement_closed')->count(),
            'movement_deleted' => ArticleAudit::where('action', 'movement_deleted')->count(),
        ];

        return view('livewire.application.stock.audit-history-viewer', [
            'audits' => $audits,
            'stats' => $stats,
            'articles' => Article::orderBy('name')->get(),
            'users' => \App\Models\User::orderBy('name')->get(),
        ]);
    }
}
