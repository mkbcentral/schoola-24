# Script de migration du module Stock
# Date: 08/11/2025

Write-Host "=== MIGRATION DU MODULE STOCK ===" -ForegroundColor Cyan
Write-Host ""

# Vérifier si les nouveaux fichiers existent
Write-Host "Vérification des nouveaux fichiers..." -ForegroundColor Yellow

$newFiles = @(
    "app\Livewire\Application\Stock\ArticleStockManager.php",
    "app\Livewire\Application\Stock\ArticleStockMovementManager.php",
    "app\Livewire\Forms\ArticleForm.php",
    "app\Livewire\Forms\StockMovementForm.php",
    "resources\views\livewire\application\stock\article-stock-manager.blade.php",
    "resources\views\livewire\application\stock\article-stock-movement-manager.blade.php"
)

$allFilesExist = $true
foreach ($file in $newFiles) {
    if (Test-Path $file) {
        Write-Host "  ✓ $file" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $file (MANQUANT)" -ForegroundColor Red
        $allFilesExist = $false
    }
}

Write-Host ""

if (-not $allFilesExist) {
    Write-Host "ERREUR: Certains fichiers sont manquants. Migration annulée." -ForegroundColor Red
    exit 1
}

# Demander confirmation
Write-Host "Les anciens fichiers suivants seront supprimés:" -ForegroundColor Yellow
Write-Host "  - app\Livewire\Stock\" -ForegroundColor Gray
Write-Host "  - resources\views\livewire\stock\" -ForegroundColor Gray
Write-Host "  - app\Http\Requests\Stock\" -ForegroundColor Gray
Write-Host ""

$confirmation = Read-Host "Continuer? (O/N)"
if ($confirmation -ne 'O' -and $confirmation -ne 'o') {
    Write-Host "Migration annulée." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Suppression des anciens fichiers..." -ForegroundColor Yellow

# Supprimer les anciens composants Livewire
if (Test-Path "app\Livewire\Stock") {
    Remove-Item -Path "app\Livewire\Stock" -Recurse -Force
    Write-Host "  ✓ app\Livewire\Stock supprimé" -ForegroundColor Green
}

# Supprimer les anciennes vues
if (Test-Path "resources\views\livewire\stock") {
    Remove-Item -Path "resources\views\livewire\stock" -Recurse -Force
    Write-Host "  ✓ resources\views\livewire\stock supprimé" -ForegroundColor Green
}

# Supprimer les Requests (si existent)
if (Test-Path "app\Http\Requests\Stock") {
    Remove-Item -Path "app\Http\Requests\Stock" -Recurse -Force
    Write-Host "  ✓ app\Http\Requests\Stock supprimé" -ForegroundColor Green
}

Write-Host ""
Write-Host "=== MIGRATION TERMINÉE AVEC SUCCÈS ===" -ForegroundColor Green
Write-Host ""
Write-Host "Actions restantes:" -ForegroundColor Cyan
Write-Host "  1. Mettre à jour les routes si nécessaire" -ForegroundColor White
Write-Host "     Ancien: \App\Livewire\Stock\ArticleStockManager::class" -ForegroundColor Gray
Write-Host "     Nouveau: \App\Livewire\Application\Stock\ArticleStockManager::class" -ForegroundColor Gray
Write-Host ""
Write-Host "  2. Vérifier les imports dans d'autres fichiers" -ForegroundColor White
Write-Host ""
Write-Host "  3. Consulter STOCK_MIGRATION.md pour plus de détails" -ForegroundColor White
Write-Host ""
Write-Host "Appuyez sur une touche pour continuer..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
