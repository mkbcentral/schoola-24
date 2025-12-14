# Script de Test - Quick Payment Theme
# Test toutes les fonctionnalités du système de thème

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Test Quick Payment Theme Support" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Vérifier les fichiers créés
Write-Host "[1/6] Vérification des fichiers..." -ForegroundColor Yellow

$files = @(
    "resources\css\quick-payment-theme.css",
    "resources\js\theme-switcher.js",
    "resources\views\components\theme-toggle.blade.php",
    "public\theme-test.html",
    "QUICK_PAYMENT_THEME_GUIDE.md",
    "THEME_IMPLEMENTATION_SUMMARY.md"
)

$allFilesExist = $true
foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "  ✓ $file" -ForegroundColor Green
    }
    else {
        Write-Host "  ✗ $file (MANQUANT)" -ForegroundColor Red
        $allFilesExist = $false
    }
}

Write-Host ""

# 2. Vérifier les modifications dans app.js
Write-Host "[2/6] Vérification de app.js..." -ForegroundColor Yellow

$appJsContent = Get-Content "resources\js\app.js" -Raw
if ($appJsContent -match "theme-switcher\.js") {
    Write-Host "  ✓ Import theme-switcher.js trouvé" -ForegroundColor Green
}
else {
    Write-Host "  ✗ Import theme-switcher.js manquant" -ForegroundColor Red
}

Write-Host ""

# 3. Vérifier les vues Blade modifiées
Write-Host "[3/6] Vérification des vues Blade..." -ForegroundColor Yellow

$bladeFiles = @(
    @{Path = "resources\views\livewire\application\payment\quick-payment-page.blade.php"; Pattern = "quick-payment-card" },
    @{Path = "resources\views\livewire\application\payment\payment-form-component.blade.php"; Pattern = "qp-form-control" },
    @{Path = "resources\views\livewire\application\payment\daily-payment-list.blade.php"; Pattern = "qp-payment-list-card" },
    @{Path = "resources\views\components\layouts\partials\navbar.blade.php"; Pattern = "theme-toggle" }
)

foreach ($blade in $bladeFiles) {
    $content = Get-Content $blade.Path -Raw -ErrorAction SilentlyContinue
    if ($content -match $blade.Pattern) {
        Write-Host "  ✓ $($blade.Path) - Classes appliquées" -ForegroundColor Green
    }
    else {
        Write-Host "  ✗ $($blade.Path) - Classes manquantes" -ForegroundColor Red
    }
}

Write-Host ""

# 4. Statistiques des fichiers
Write-Host "[4/6] Statistiques..." -ForegroundColor Yellow

$cssLines = (Get-Content "resources\css\quick-payment-theme.css" | Measure-Object -Line).Lines
$jsLines = (Get-Content "resources\js\theme-switcher.js" | Measure-Object -Line).Lines
$guideLines = (Get-Content "QUICK_PAYMENT_THEME_GUIDE.md" | Measure-Object -Line).Lines

Write-Host "  - CSS: $cssLines lignes" -ForegroundColor Cyan
Write-Host "  - JavaScript: $jsLines lignes" -ForegroundColor Cyan
Write-Host "  - Documentation: $guideLines lignes" -ForegroundColor Cyan

Write-Host ""

# 5. Vérifier si Vite est en cours d'exécution
Write-Host "[5/6] Vérification du serveur Vite..." -ForegroundColor Yellow

try {
    $viteProcess = Get-Process -Name "node" -ErrorAction SilentlyContinue | Where-Object {
        $_.MainWindowTitle -like "*vite*" -or $_.CommandLine -like "*vite*"
    }
    
    if ($viteProcess) {
        Write-Host "  ✓ Vite est en cours d'exécution" -ForegroundColor Green
    }
    else {
        Write-Host "  ⚠ Vite ne semble pas être en cours d'exécution" -ForegroundColor Yellow
        Write-Host "    Lancez: npm run dev" -ForegroundColor Gray
    }
}
catch {
    Write-Host "  ⚠ Impossible de vérifier Vite" -ForegroundColor Yellow
}

Write-Host ""

# 6. Vérifier les assets compilés
Write-Host "[6/6] Vérification des assets..." -ForegroundColor Yellow

if (Test-Path "public\build") {
    Write-Host "  ✓ Dossier public\build existe" -ForegroundColor Green
    
    $manifestPath = "public\build\manifest.json"
    if (Test-Path $manifestPath) {
        Write-Host "  ✓ manifest.json trouvé" -ForegroundColor Green
    }
    else {
        Write-Host "  ⚠ manifest.json manquant (pas encore compilé)" -ForegroundColor Yellow
    }
}
else {
    Write-Host "  ⚠ Assets pas encore compilés" -ForegroundColor Yellow
    Write-Host "    Lancez: npm run build" -ForegroundColor Gray
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Résumé" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

if ($allFilesExist) {
    Write-Host "✓ Tous les fichiers sont présents" -ForegroundColor Green
}
else {
    Write-Host "✗ Certains fichiers sont manquants" -ForegroundColor Red
}

Write-Host ""
Write-Host "Prochaines étapes:" -ForegroundColor Yellow
Write-Host "  1. Compiler les assets: npm run build" -ForegroundColor White
Write-Host "  2. Lancer le serveur: php artisan serve" -ForegroundColor White
Write-Host "  3. Tester: http://localhost:8000/payment/quick" -ForegroundColor White
Write-Host "  4. Page de test: http://localhost:8000/theme-test.html" -ForegroundColor White
Write-Host ""

# Ouvrir automatiquement les pages de documentation
Write-Host "Voulez-vous ouvrir les guides de documentation? (O/N): " -ForegroundColor Cyan -NoNewline
$response = Read-Host

if ($response -eq "O" -or $response -eq "o") {
    if (Test-Path "QUICK_PAYMENT_THEME_GUIDE.md") {
        Start-Process "QUICK_PAYMENT_THEME_GUIDE.md"
    }
    if (Test-Path "THEME_IMPLEMENTATION_SUMMARY.md") {
        Start-Process "THEME_IMPLEMENTATION_SUMMARY.md"
    }
    Write-Host "Documentation ouverte!" -ForegroundColor Green
}

Write-Host ""
Write-Host "Test termine!" -ForegroundColor Green
