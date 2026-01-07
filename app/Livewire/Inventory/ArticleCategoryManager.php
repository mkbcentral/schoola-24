<?php

namespace App\Livewire\Inventory;

use App\Livewire\Forms\CategoryForm;
use App\Models\ArticleCategory;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleCategoryManager extends Component
{
    use WithPagination;

    public CategoryForm $form;

    public $editMode = false;

    // Couleurs prédéfinies suggérées
    public $suggestedColors = [
        '#0dcaf0',
        '#0d6efd',
        '#6610f2',
        '#6f42c1',
        '#d63384',
        '#dc3545',
        '#fd7e14',
        '#ffc107',
        '#198754',
        '#20c997',
        '#6c757d',
        '#212529',
        '#e83e8c',
        '#17a2b8',
        '#28a745',
    ];

    /**
     * Créer une nouvelle catégorie
     */
    public function createCategory()
    {
        try {
            $this->form->create();

            $this->dispatch('added', ['message' => 'Catégorie créée avec succès !']);
            $this->resetForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Préparer l'édition d'une catégorie
     */
    public function editCategory($id)
    {
        try {
            $category = ArticleCategory::findOrFail($id);
            $this->form->setCategory($category);
            $this->editMode = true;
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Mettre à jour une catégorie
     */
    public function updateCategory()
    {
        try {
            $this->form->update();

            $this->dispatch('added', ['message' => 'Catégorie modifiée avec succès !']);
            $this->resetForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une catégorie
     */
    public function deleteCategory($id)
    {
        try {
            $category = ArticleCategory::findOrFail($id);

            // Vérifier s'il y a des articles liés
            if ($category->articles()->count() > 0) {
                $this->dispatch('error', ['message' => 'Impossible de supprimer : des articles sont liés à cette catégorie']);

                return;
            }

            $category->delete();
            $this->dispatch('success', ['message' => 'Catégorie supprimée avec succès !']);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur : ' . $e->getMessage()]);
        }
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
     * Obtenir les catégories avec pagination
     */
    public function getCategoriesProperty()
    {
        return ArticleCategory::withCount('articles')
            ->orderBy('name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.application.stock.article-category-manager', [
            'categories' => $this->categories,
        ]);
    }
}
