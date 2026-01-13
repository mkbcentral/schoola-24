#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Ajoute la méthode render() explicite aux composants Livewire qui n'en ont pas
.DESCRIPTION
    Analyse tous les composants Livewire et ajoute une méthode render() pointant vers la vue correcte
#>

$ErrorActionPreference = "Stop"
$componentsFixed = 0
$componentsSkipped = 0
$errors = @()

# Mapping des chemins de vues connus
$viewMapping = @{
    'Academic\Fee\FeeManagementPage' = 'application.v2.fee.fee-management-page'
    'Academic\Fee\MainScolarFeePage' = 'application.fee.scolar.main-scolar-fee-page'
    'Academic\Student\StudentInfoPage' = 'application.student.student-info-page-tailwind'
    'Academic\Student\DetailStudentPage' = 'application.student.detail-student-page'
    'Academic\Student\ListStudentDebtPage' = 'application.v2.report.list-student-debt-page-tailwind'
    'Academic\Registration\RegistrationListPage' = 'application.v2.registration.registration-list-page'
    'Academic\Registration\ListRegistrationByClassRoomPage' = 'application.registration.list.list-registration-by-class-room-page'
    'Academic\Registration\ListRegistrationByDatePage' = 'application.registration.list.list-registration-by-date-page'
    'Academic\Registration\ListRegistrationByMonthPage' = 'application.registration.list.list-registration-by-month-page'
    'Financial\Dashboard\FinancialDashboardPage' = 'application.dashboard.finance.financial-dashboard-page'
    'Financial\Expense\ExpenseManagementPage' = 'application.v2.expense.expense-management-page'
    'Financial\Expense\Settings\ExpenseSettingsPage' = 'application.finance.expense.settings.expense-settings-page'
    'Financial\Payment\QuickPaymentPage' = 'application.payment.quick-payment-page-tailwind'
    'Financial\Payment\PaymentListPage' = 'application.payment.payment-list-page-tailwind'
    'Financial\Payment\Report\PaymentReportPage' = 'application.payment.report.payment-report-page-tailwind'
    'Financial\Report\TreasuryReportPage' = 'application.v2.report.treasury-report'
    'Financial\Report\ProfitabilityReportPage' = 'application.v2.report.profitability-report'
    'Financial\Report\ForecastReportPage' = 'application.v2.report.forecast-report'
    'Financial\Report\ComparisonReportPage' = 'application.v2.report.comparison-report'
    'Configuration\Settings\MainSettingPage' = 'application.setting.main-setting-page'
    'Configuration\System\SectionManagementPage' = 'application.v2.configuration.section-management-page'
    'Configuration\System\ConfigurationManagementPage' = 'application.v2.configuration.configuration-management-page'
    'Admin\User\UserManagementPage' = 'application.v2.user.user-management-page'
    'Admin\User\UserProfilePage' = 'application.v2.user.user-profile-page'
    'Inventory\StockDashboard' = 'application.v2.stock.stock-dashboard'
    'Inventory\ArticleCategoryManager' = 'application.v2.stock.article-category-manager'
    'Inventory\ArticleInventoryManager' = 'application.v2.stock.article-inventory-manager'
    'Inventory\ArticleStockManager' = 'application.v2.stock.article-stock-manager'
    'Inventory\ArticleStockMovementManager' = 'application.v2.stock.article-stock-movement-manager'
    'Inventory\AuditHistoryViewer' = 'application.v2.audit.stock.audit-history-viewer'
}

function Add-RenderMethod {
    param(
        [string]$FilePath,
        [string]$ViewPath
    )
    
    $content = Get-Content $FilePath -Raw
    
    # Vérifier si la méthode render existe déjà
    if ($content -match 'public\s+function\s+render\s*\(\s*\)') {
        return $false
    }
    
    # Trouver la position avant la dernière accolade fermante
    $lastBracePos = $content.LastIndexOf('}')
    
    if ($lastBracePos -eq -1) {
        throw "Impossible de trouver la dernière accolade dans $FilePath"
    }
    
    # Insérer la méthode render avant la dernière accolade
    $renderMethod = @"

    public function render()
    {
        return view('livewire.$ViewPath');
    }
"@
    
    $newContent = $content.Insert($lastBracePos, $renderMethod)
    
    # Sauvegarder le fichier
    Set-Content -Path $FilePath -Value $newContent -NoNewline
    
    return $true
}

Write-Host "`n=== CORRECTION DES COMPOSANTS LIVEWIRE ===`n" -ForegroundColor Cyan

$components = Get-ChildItem -Path "app\Livewire" -Recurse -Filter "*.php" | 
    Where-Object { $_.Directory.Name -ne "Traits" }

Write-Host "Composants trouvés: $($components.Count)`n" -ForegroundColor Yellow

foreach ($comp in $components) {
    $content = Get-Content $comp.FullName -Raw
    
    if ($content -match 'namespace\s+App\\Livewire\\([^;]+)') {
        $namespace = $matches[1]
        $relativePath = $namespace -replace '\\', '\'
        
        # Chercher dans le mapping
        $viewPath = $viewMapping[$relativePath]
        
        if ($viewPath) {
            # Vérifier si la vue existe
            $viewFile = "resources\views\livewire\$($viewPath -replace '\.', '\').blade.php"
            
            if (Test-Path $viewFile) {
                try {
                    $fixed = Add-RenderMethod -FilePath $comp.FullName -ViewPath $viewPath
                    
                    if ($fixed) {
                        Write-Host "✓ " -ForegroundColor Green -NoNewline
                        Write-Host "$relativePath → livewire.$viewPath"
                        $componentsFixed++
                    } else {
                        Write-Host "○ " -ForegroundColor Gray -NoNewline
                        Write-Host "$relativePath (déjà corrigé)"
                        $componentsSkipped++
                    }
                } catch {
                    Write-Host "✗ " -ForegroundColor Red -NoNewline
                    Write-Host "$relativePath - Erreur: $($_.Exception.Message)"
                    $errors += $relativePath
                }
            } else {
                Write-Host "⚠ " -ForegroundColor Yellow -NoNewline
                Write-Host "$relativePath - Vue introuvable: $viewFile"
            }
        } else {
            Write-Host "? " -ForegroundColor Magenta -NoNewline
            Write-Host "$relativePath - Pas de mapping défini"
        }
    }
}

Write-Host "`n=== RÉSUMÉ ===`n" -ForegroundColor Cyan
Write-Host "Composants corrigés: $componentsFixed" -ForegroundColor Green
Write-Host "Déjà corrigés: $componentsSkipped" -ForegroundColor Gray
Write-Host "Erreurs: $($errors.Count)" -ForegroundColor Red

if ($errors.Count -gt 0) {
    Write-Host "`nComposants en erreur:" -ForegroundColor Red
    $errors | ForEach-Object { Write-Host "  - $_" }
}

Write-Host "`n✓ Script terminé`n" -ForegroundColor Green
