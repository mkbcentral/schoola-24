<?php

namespace App\Livewire\Stock;

use App\Models\Article;
use App\Models\ArticleStockMovement;
use App\Models\School;
use App\Models\SchoolYear;
use Auth;
use Exception;
use Livewire\Component;

class ArticleStockManager extends Component

// Créer un nouvel article
{


    public $showMovements = false;
    public $selectedArticleName = '';
    public $selectedArticle;
    public $articles = [];
    public $article_id;
    public $name = '';
    public $reference = '';
    public $unit = '';
    public $description = '';
    public $editMode = false;

    public function mount()
    {
        $this->articles = Article::orderBy('name')->get();
    }

    public function createArticle()
    {
        $this->validate([
            'name' => 'required|string',
        ]);
        Article::create([
            'name' => $this->name,
            'reference' => $this->reference,
            'unit' => $this->unit,
            'description' => $this->description,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
        ]);
        $this->articles = Article::orderBy('name')->get();
        $this->resetForm();
    }


    public function editArticle($id)
    {
        $article = Article::findOrFail($id);
        $this->article_id = $article->id;
        $this->name = $article->name;
        $this->reference = $article->reference;
        $this->unit = $article->unit;
        $this->description = $article->description;
        $this->editMode = true;
    }

    public function updateArticle()
    {
        $this->validate([
            'name' => 'required|string',
        ]);
        $article = Article::findOrFail($this->article_id);
        $article->update([
            'name' => $this->name,
            'reference' => $this->reference,
            'unit' => $this->unit,
            'description' => $this->description,
        ]);
        $this->articles = Article::orderBy('name')->get();
        $this->resetForm();
    }

    public function deleteArticle($id)
    {
        Article::findOrFail($id)->delete();
        $this->articles = Article::orderBy('name')->get();
        $this->resetForm();
    }

    public function showStockMovements($id)
    {
        $this->selectedArticle = Article::findOrFail($id);
        $this->selectedArticleName = $this->selectedArticle->name;
        $this->showMovements = true;
    }

    //reset form article
    public function resetForm()
    {
        $this->article_id = null;
        $this->name = '';
        $this->reference = '';
        $this->unit = '';
        $this->description = '';
        $this->editMode = false;
    }
}
