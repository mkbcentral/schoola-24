# Script de Migration de l'Architecture Livewire
# Usage: .\migrate-component.ps1 -Component "PaymentListPage" -From "Application\Payment" -To "Financial\Payment"

param(
    [Parameter(Mandatory=$false)]
    [string]$Component,
    
    [Parameter(Mandatory=$false)]
    [string]$From,
    
    [Parameter(Mandatory=$false)]
    [string]$To,
    
    [Parameter(Mandatory=$false)]
    [switch]$DryRun,
    
    [Parameter(Mandatory=$false)]
    [switch]$ShowMapping
)

$basePath = "d:\dev\schoola\schoola-web"
$livewirePath = Join-Path $basePath "app\Livewire"

# ============================================================
# MAPPING DE MIGRATION
# ============================================================
$migrationMapping = @{
    # Domaine Financial
    "Application\Dashboard\Finance" = "Financial\Dashboard"
    "Application\Payment" = "Financial\Payment"
    "Application\Finance\Expense" = "Financial\Expense"
    "Application\AllReport" = "Financial\Report"
    
    # Domaine Academic
    "Application\Student" = "Academic\Student"
    "Application\Registration" = "Academic\Registration"
    "Application\Fee\Scolar" = "Academic\Fee"
    "Application\V2\Report" = "Academic\Report"
    
    # Domaine Inventory
    "Application\Stock" = "Inventory"
    
    # Domaine Admin
    "Application\Admin" = "Admin\User"
    "Application\Admin\School" = "Admin\School"
    "Application\V2\User" = "Admin\User"
    "Application\V2\School" = "Admin\School"
    
    # Configuration
    "Application\Setting" = "Configuration\Settings"
    "Application\V2\Configuration" = "Configuration\System"
    "Application\V2\Fee" = "Configuration\Fee"
}

# ============================================================
# FONCTION: Afficher le mapping
# ============================================================
function Show-MigrationMapping {
    Write-Host "`n=== MAPPING DE MIGRATION ===" -ForegroundColor Cyan
    Write-Host ""
    
    $domaines = @{
        "Financial" = @()
        "Academic" = @()
        "Inventory" = @()
        "Admin" = @()
        "Configuration" = @()
    }
    
    foreach ($key in $migrationMapping.Keys | Sort-Object) {
        $value = $migrationMapping[$key]
        $domaine = $value.Split('\')[0]
        
        if ($domaines.ContainsKey($domaine)) {
            $domaines[$domaine] += [PSCustomObject]@{
                From = $key
                To = $value
            }
        }
    }
    
    foreach ($domaine in $domaines.Keys | Sort-Object) {
        if ($domaines[$domaine].Count -gt 0) {
            Write-Host "📁 $domaine" -ForegroundColor Yellow
            foreach ($item in $domaines[$domaine]) {
                Write-Host "   $($item.From)" -ForegroundColor Gray -NoNewline
                Write-Host " → " -ForegroundColor White -NoNewline
                Write-Host "$($item.To)" -ForegroundColor Green
            }
            Write-Host ""
        }
    }
}

# ============================================================
# FONCTION: Migrer un composant
# ============================================================
function Migrate-Component {
    param(
        [string]$ComponentName,
        [string]$SourcePath,
        [string]$DestinationPath,
        [bool]$IsDryRun
    )
    
    $sourceFile = Join-Path $livewirePath "$SourcePath\$ComponentName.php"
    $destDir = Join-Path $livewirePath $DestinationPath
    $destFile = Join-Path $destDir "$ComponentName.php"
    
    # Vérifier que le fichier source existe
    if (-not (Test-Path $sourceFile)) {
        Write-Host "❌ Fichier source introuvable: $sourceFile" -ForegroundColor Red
        return $false
    }
    
    if ($IsDryRun) {
        Write-Host "🔍 [DRY RUN] Migration:" -ForegroundColor Yellow
        Write-Host "   Source: $sourceFile" -ForegroundColor Gray
        Write-Host "   Destination: $destFile" -ForegroundColor Gray
        return $true
    }
    
    # Créer le dossier de destination
    if (-not (Test-Path $destDir)) {
        New-Item -Path $destDir -ItemType Directory -Force | Out-Null
        Write-Host "📁 Dossier créé: $destDir" -ForegroundColor Green
    }
    
    # Lire le contenu du fichier
    $content = Get-Content $sourceFile -Raw
    
    # Mettre à jour le namespace
    $oldNamespace = "App\Livewire\$($SourcePath -replace '\\','\\')"
    $newNamespace = "App\Livewire\$($DestinationPath -replace '\\','\\')"
    $content = $content -replace "namespace $oldNamespace;", "namespace $newNamespace;"
    
    # Écrire le nouveau fichier
    Set-Content -Path $destFile -Value $content -NoNewline
    
    Write-Host "✅ Migré: $ComponentName" -ForegroundColor Green
    Write-Host "   $SourcePath → $DestinationPath" -ForegroundColor Gray
    
    # Rechercher les fichiers qui importent ce composant
    Update-Imports -ComponentName $ComponentName -OldPath $SourcePath -NewPath $DestinationPath -IsDryRun $IsDryRun
    
    return $true
}

# ============================================================
# FONCTION: Mettre à jour les imports
# ============================================================
function Update-Imports {
    param(
        [string]$ComponentName,
        [string]$OldPath,
        [string]$NewPath,
        [bool]$IsDryRun
    )
    
    $oldImport = "App\Livewire\$($OldPath -replace '\\','\\')\\$ComponentName"
    $newImport = "App\Livewire\$($NewPath -replace '\\','\\')\\$ComponentName"
    
    # Rechercher dans les fichiers PHP
    $filesToUpdate = Get-ChildItem -Path $basePath -Recurse -Include *.php,*.blade.php -File |
        Where-Object { $_.FullName -notlike "*\vendor\*" -and $_.FullName -notlike "*\storage\*" } |
        Where-Object { (Get-Content $_.FullName -Raw) -match [regex]::Escape($oldImport) }
    
    if ($filesToUpdate.Count -eq 0) {
        return
    }
    
    Write-Host "   📝 Fichiers à mettre à jour: $($filesToUpdate.Count)" -ForegroundColor Cyan
    
    foreach ($file in $filesToUpdate) {
        if ($IsDryRun) {
            Write-Host "      - $($file.Name)" -ForegroundColor Gray
        } else {
            $content = Get-Content $file.FullName -Raw
            $content = $content -replace [regex]::Escape($oldImport), $newImport
            Set-Content -Path $file.FullName -Value $content -NoNewline
            Write-Host "      ✅ $($file.Name)" -ForegroundColor Green
        }
    }
}

# ============================================================
# FONCTION: Migrer automatiquement selon le mapping
# ============================================================
function Migrate-AllComponents {
    param([bool]$IsDryRun)
    
    Write-Host "`n=== MIGRATION AUTOMATIQUE ===" -ForegroundColor Cyan
    Write-Host ""
    
    $totalMigrated = 0
    $totalFailed = 0
    
    foreach ($source in $migrationMapping.Keys | Sort-Object) {
        $destination = $migrationMapping[$source]
        $sourcePath = Join-Path $livewirePath $source
        
        if (-not (Test-Path $sourcePath)) {
            Write-Host "⚠️  Dossier source introuvable: $source" -ForegroundColor Yellow
            continue
        }
        
        Write-Host "📁 Migration: $source → $destination" -ForegroundColor Yellow
        
        $files = Get-ChildItem -Path $sourcePath -Filter *.php -File
        
        foreach ($file in $files) {
            $componentName = $file.BaseName
            
            if (Migrate-Component -ComponentName $componentName -SourcePath $source -DestinationPath $destination -IsDryRun $IsDryRun) {
                $totalMigrated++
            } else {
                $totalFailed++
            }
        }
        
        Write-Host ""
    }
    
    Write-Host "`n=== RÉSUMÉ ===" -ForegroundColor Cyan
    Write-Host "✅ Composants migrés: $totalMigrated" -ForegroundColor Green
    if ($totalFailed -gt 0) {
        Write-Host "❌ Échecs: $totalFailed" -ForegroundColor Red
    }
}

# ============================================================
# FONCTION: Nettoyer les dossiers vides
# ============================================================
function Remove-EmptyDirectories {
    param([bool]$IsDryRun)
    
    Write-Host "`n=== NETTOYAGE DES DOSSIERS VIDES ===" -ForegroundColor Cyan
    
    $emptyDirs = Get-ChildItem -Path $livewirePath -Directory -Recurse |
        Where-Object { (Get-ChildItem $_.FullName -Recurse -File).Count -eq 0 } |
        Sort-Object -Property FullName -Descending
    
    foreach ($dir in $emptyDirs) {
        $relativePath = $dir.FullName.Replace($livewirePath + '\', '')
        
        if ($IsDryRun) {
            Write-Host "🗑️  [DRY RUN] Supprimerait: $relativePath" -ForegroundColor Gray
        } else {
            Remove-Item $dir.FullName -Force -Recurse
            Write-Host "✅ Supprimé: $relativePath" -ForegroundColor Green
        }
    }
}

# ============================================================
# MAIN
# ============================================================

Write-Host @"
╔═══════════════════════════════════════════════════════════════╗
║     SCRIPT DE MIGRATION D'ARCHITECTURE LIVEWIRE              ║
║                 Schoola Web - 2026                           ║
╚═══════════════════════════════════════════════════════════════╝
"@ -ForegroundColor Cyan

if ($ShowMapping) {
    Show-MigrationMapping
    exit
}

if ($DryRun) {
    Write-Host "`n⚠️  MODE DRY RUN - Aucune modification ne sera effectuée`n" -ForegroundColor Yellow
}

if ($Component -and $From -and $To) {
    # Migration d'un composant spécifique
    Migrate-Component -ComponentName $Component -SourcePath $From -DestinationPath $To -IsDryRun $DryRun
} else {
    # Migration automatique de tous les composants
    Migrate-AllComponents -IsDryRun $DryRun
    Remove-EmptyDirectories -IsDryRun $DryRun
}

Write-Host "`n✅ Migration terminée!`n" -ForegroundColor Green

# ============================================================
# EXEMPLES D'UTILISATION
# ============================================================
<#
# Afficher le mapping
.\migrate-component.ps1 -ShowMapping

# Tester la migration (dry run)
.\migrate-component.ps1 -DryRun

# Migrer un composant spécifique
.\migrate-component.ps1 -Component "PaymentListPage" -From "Application\Payment" -To "Financial\Payment"

# Migrer tous les composants
.\migrate-component.ps1

# Migrer avec dry run
.\migrate-component.ps1 -DryRun
#>
