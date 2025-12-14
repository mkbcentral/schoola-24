# Script de Test Simplifié - Quick Payment Theme

Write-Host "========================================"
Write-Host "  Test Quick Payment Theme Support"
Write-Host "========================================"
Write-Host ""

# Vérifier les fichiers créés
Write-Host "[1/4] Vérification des fichiers..."

$files = @(
    "resources\css\quick-payment-theme.css",
    "resources\js\theme-switcher.js",
    "resources\views\components\theme-toggle.blade.php",
    "public\theme-test.html"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "  OK: $file" -ForegroundColor Green
    }
    else {
        Write-Host "  MANQUANT: $file" -ForegroundColor Red
    }
}

Write-Host ""

# Vérifier app.js
Write-Host "[2/4] Vérification de app.js..."

$appJsContent = Get-Content "resources\js\app.js" -Raw
if ($appJsContent -match "theme-switcher") {
    Write-Host "  OK: Import theme-switcher trouvé" -ForegroundColor Green
}
else {
    Write-Host "  ERREUR: Import manquant" -ForegroundColor Red
}

Write-Host ""

# Statistiques
Write-Host "[3/4] Statistiques..."

$cssLines = (Get-Content "resources\css\quick-payment-theme.css" | Measure-Object -Line).Lines
$jsLines = (Get-Content "resources\js\theme-switcher.js" | Measure-Object -Line).Lines

Write-Host "  - CSS: $cssLines lignes"
Write-Host "  - JavaScript: $jsLines lignes"

Write-Host ""

# Instructions
Write-Host "[4/4] Prochaines étapes..."
Write-Host "  1. Compiler: npm run build"
Write-Host "  2. Serveur: php artisan serve"
Write-Host "  3. Tester: http://localhost:8000/payment/quick"
Write-Host "  4. Demo: http://localhost:8000/theme-test.html"

Write-Host ""
Write-Host "Test termine!" -ForegroundColor Green
