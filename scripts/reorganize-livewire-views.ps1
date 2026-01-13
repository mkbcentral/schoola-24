#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Réorganise les vues Livewire pour correspondre aux namespaces
.DESCRIPTION
    Déplace les fichiers de views/livewire/application/* vers views/livewire/* 
    pour correspondre exactement à la structure des namespaces
#>

param(
    [switch]$DryRun = $true,  # Par défaut, simulation seulement
    [switch]$Execute = $false  # Passer -Execute pour vraiment déplacer les fichiers
)

$ErrorActionPreference = "Stop"
$moved = 0
$skipped = 0
$errors = @()

# Mapping des déplacements à effectuer
$moveOperations = @(
    @{ From = 'resources\views\livewire\application\v2\fee\fee-management-page.blade.php'; To = 'resources\views\livewire\academic\fee\feemanagement-page.blade.php' }
    @{ From = 'resources\views\livewire\application\fee\scolar\main-scolar-fee-page.blade.php'; To = 'resources\views\livewire\academic\fee\mainscolarfee-page.blade.php' }
    @{ From = 'resources\views\livewire\application\student\student-info-page-tailwind.blade.php'; To = 'resources\views\livewire\academic\student\studentinfo-page.blade.php' }
    @{ From = 'resources\views\livewire\application\student\detail-student-page.blade.php'; To = 'resources\views\livewire\academic\student\detailstudent-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\report\list-student-debt-page-tailwind.blade.php'; To = 'resources\views\livewire\academic\student\liststudentdebt-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\registration\registration-list-page.blade.php'; To = 'resources\views\livewire\academic\registration\registrationlist-page.blade.php' }
    @{ From = 'resources\views\livewire\application\registration\list\list-registration-by-class-room-page.blade.php'; To = 'resources\views\livewire\academic\registration\listregistrationbyclassroom-page.blade.php' }
    @{ From = 'resources\views\livewire\application\registration\list\list-registration-by-date-page.blade.php'; To = 'resources\views\livewire\academic\registration\listregistrationbydate-page.blade.php' }
    @{ From = 'resources\views\livewire\application\registration\list\list-registration-by-month-page.blade.php'; To = 'resources\views\livewire\academic\registration\listregistrationbymonth-page.blade.php' }
    @{ From = 'resources\views\livewire\application\dashboard\finance\financial-dashboard-page.blade.php'; To = 'resources\views\livewire\financial\dashboard\financialdashboard-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\expense\expense-management-page.blade.php'; To = 'resources\views\livewire\financial\expense\expensemanagement-page.blade.php' }
    @{ From = 'resources\views\livewire\application\finance\expense\settings\expense-settings-page.blade.php'; To = 'resources\views\livewire\financial\expense\settings\expensesettings-page.blade.php' }
    @{ From = 'resources\views\livewire\application\payment\quick-payment-page-tailwind.blade.php'; To = 'resources\views\livewire\financial\payment\quickpayment-page.blade.php' }
    @{ From = 'resources\views\livewire\application\payment\payment-list-page-tailwind.blade.php'; To = 'resources\views\livewire\financial\payment\paymentlist-page.blade.php' }
    @{ From = 'resources\views\livewire\application\payment\report\payment-report-page-tailwind.blade.php'; To = 'resources\views\livewire\financial\payment\report\paymentreport-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\report\treasury-report.blade.php'; To = 'resources\views\livewire\financial\report\treasuryreport-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\report\profitability-report.blade.php'; To = 'resources\views\livewire\financial\report\profitabilityreport-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\report\forecast-report.blade.php'; To = 'resources\views\livewire\financial\report\forecastreport-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\report\comparison-report.blade.php'; To = 'resources\views\livewire\financial\report\comparisonreport-page.blade.php' }
    @{ From = 'resources\views\livewire\application\setting\main-setting-page.blade.php'; To = 'resources\views\livewire\configuration\settings\mainsetting-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\configuration\section-management-page.blade.php'; To = 'resources\views\livewire\configuration\system\sectionmanagement-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\configuration\configuration-management-page.blade.php'; To = 'resources\views\livewire\configuration\system\configurationmanagement-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\user\user-management-page.blade.php'; To = 'resources\views\livewire\admin\user\usermanagement-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\user\user-profile-page.blade.php'; To = 'resources\views\livewire\admin\user\userprofile-page.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\stock\stock-dashboard.blade.php'; To = 'resources\views\livewire\inventory\stockdashboard.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\stock\article-category-manager.blade.php'; To = 'resources\views\livewire\inventory\articlecategorymanager.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\stock\article-inventory-manager.blade.php'; To = 'resources\views\livewire\inventory\articleinventorymanager.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\stock\article-stock-manager.blade.php'; To = 'resources\views\livewire\inventory\articlestockmanager.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\stock\article-stock-movement-manager.blade.php'; To = 'resources\views\livewire\inventory\articlestockmovementmanager.blade.php' }
    @{ From = 'resources\views\livewire\application\v2\audit\stock\audit-history-viewer.blade.php'; To = 'resources\views\livewire\inventory\audithistoryviewer.blade.php' }
)

if ($DryRun -and -not $Execute) {
    Write-Host "`n⚠ MODE SIMULATION - Aucun fichier ne sera déplacé" -ForegroundColor Yellow
    Write-Host "Utilisez -Execute pour effectuer les déplacements réels`n" -ForegroundColor Yellow
} else {
    Write-Host "`n⚠ MODE EXÉCUTION - Les fichiers seront déplacés!" -ForegroundColor Red
    Write-Host "Appuyez sur Ctrl+C pour annuler dans les 5 secondes...`n" -ForegroundColor Red
    Start-Sleep -Seconds 5
}

Write-Host "=== RÉORGANISATION DES VUES LIVEWIRE ===`n" -ForegroundColor Cyan

foreach ($op in $moveOperations) {
    $from = $op.From
    $to = $op.To
    
    if (Test-Path $from) {
        $toDir = Split-Path $to -Parent
        
        if ($Execute -and -not $DryRun) {
            try {
                # Créer le dossier de destination
                if (-not (Test-Path $toDir)) {
                    New-Item -ItemType Directory -Path $toDir -Force | Out-Null
                }
                
                # Déplacer le fichier
                Move-Item -Path $from -Destination $to -Force
                
                Write-Host "✓ " -ForegroundColor Green -NoNewline
                Write-Host "Déplacé: $from → $to"
                $moved++
            } catch {
                Write-Host "✗ " -ForegroundColor Red -NoNewline
                Write-Host "Erreur: $from - $($_.Exception.Message)"
                $errors += $from
            }
        } else {
            Write-Host "○ " -ForegroundColor Cyan -NoNewline
            Write-Host "Simulation: $from → $to"
            $moved++
        }
    } else {
        Write-Host "⚠ " -ForegroundColor Yellow -NoNewline
        Write-Host "Fichier introuvable: $from"
        $skipped++
    }
}

Write-Host "`n=== RÉSUMÉ ===`n" -ForegroundColor Cyan
if ($Execute -and -not $DryRun) {
    Write-Host "Fichiers déplacés: $moved" -ForegroundColor Green
} else {
    Write-Host "Fichiers à déplacer: $moved" -ForegroundColor Cyan
}
Write-Host "Fichiers ignorés: $skipped" -ForegroundColor Yellow
Write-Host "Erreurs: $($errors.Count)" -ForegroundColor Red

if ($errors.Count -gt 0) {
    Write-Host "`nFichiers en erreur:" -ForegroundColor Red
    $errors | ForEach-Object { Write-Host "  - $_" }
}

if ($DryRun -and -not $Execute) {
    Write-Host "`n⚠ SIMULATION TERMINÉE - Relancez avec -Execute pour appliquer les changements`n" -ForegroundColor Yellow
} else {
    Write-Host "`n✓ Déplacements terminés`n" -ForegroundColor Green
    Write-Host "⚠ N'oubliez pas de supprimer les méthodes render() explicites dans les composants!`n" -ForegroundColor Yellow
}
