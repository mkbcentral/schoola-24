<?php

namespace App\Livewire\Forms;

use App\Models\ArticleCategory;
use App\Models\School;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?ArticleCategory $category = null;

    #[Rule('required|string|max:255', message: 'Le nom de la catégorie est obligatoire', onUpdate: false)]
    public $name = '';

    #[Rule('nullable|string|max:500', message: 'La description ne doit pas dépasser 500 caractères', onUpdate: false)]
    public $description = '';

    #[Rule('required|string|regex:/^#[0-9A-Fa-f]{6}$/', message: 'Veuillez choisir une couleur valide', onUpdate: false)]
    public $color = '#6c757d';

    /**
     * Définir la catégorie pour l'édition
     */
    public function setCategory(ArticleCategory $category): void
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->color = $category->color;
    }

    /**
     * Créer une nouvelle catégorie
     */
    public function create(): ArticleCategory
    {
        // Validation avec unicité du nom
        $this->validate([
            'name' => 'required|string|max:255|unique:article_categories,name',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        return ArticleCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);
    }

    /**
     * Mettre à jour une catégorie existante
     */
    public function update(): void
    {
        // Validation avec unicité du nom (sauf pour la catégorie actuelle)
        $this->validate([
            'name' => 'required|string|max:255|unique:article_categories,name,' . $this->category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $this->category->update([
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
        ]);
    }

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        $this->category = null;
        $this->name = '';
        $this->description = '';
        $this->color = '#6c757d';

        parent::reset(...$properties);
    }
}
