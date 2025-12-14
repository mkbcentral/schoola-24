<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ArticleForm extends Form
{
    public ?Article $article = null;

    #[Rule('required|string|max:255', message: "Le nom de l'article est obligatoire", onUpdate: false)]
    public $name = '';

    #[Rule('nullable|string|max:100', message: 'La référence ne doit pas dépasser 100 caractères', onUpdate: false)]
    public $reference = '';

    #[Rule('nullable|string|max:50', message: "L'unité ne doit pas dépasser 50 caractères", onUpdate: false)]
    public $unit = '';

    #[Rule('nullable|string|max:500', message: 'La description ne doit pas dépasser 500 caractères', onUpdate: false)]
    public $description = '';

    #[Rule('nullable|integer|min:0', message: 'Le stock minimum doit être un nombre positif', onUpdate: false)]
    public $stock_min = 0;

    #[Rule('nullable|exists:article_categories,id', message: 'Catégorie invalide', onUpdate: false)]
    public $category_id = null;

    /**
     * Définir l'article pour l'édition
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
        $this->name = $article->name;
        $this->reference = $article->reference;
        $this->unit = $article->unit;
        $this->description = $article->description;
        $this->stock_min = $article->stock_min ?? 0;
        $this->category_id = $article->category_id;
    }

    /**
     * Créer un nouvel article
     */
    public function create(): Article
    {
        $this->validate();

        return Article::create([
            'name' => $this->name,
            'reference' => $this->reference,
            'unit' => $this->unit,
            'description' => $this->description,
            'stock_min' => $this->stock_min ?? 0,
            'category_id' => $this->category_id,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
        ]);
    }

    /**
     * Mettre à jour un article existant
     */
    public function update(): void
    {
        $this->validate();

        $this->article->update([
            'name' => $this->name,
            'reference' => $this->reference,
            'unit' => $this->unit,
            'description' => $this->description,
            'stock_min' => $this->stock_min ?? 0,
            'category_id' => $this->category_id,
        ]);
    }

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        $this->article = null;
        $this->name = '';
        $this->reference = '';
        $this->unit = '';
        $this->description = '';
        $this->stock_min = 0;
        $this->category_id = null;

        parent::reset(...$properties);
    }
}
