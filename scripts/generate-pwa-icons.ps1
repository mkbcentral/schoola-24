# Script PowerShell pour générer les icônes PWA
# Nécessite ImageMagick : https://imagemagick.org/script/download.php

$sourceLogo = "public/images/Vector-white.svg"
$outputDir = "public/images/icons"

# Créer le dossier de sortie si nécessaire
if (!(Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir -Force
}

# Tailles d'icônes requises pour PWA
$sizes = @(72, 96, 128, 144, 152, 192, 384, 512)

Write-Host "Génération des icônes PWA..." -ForegroundColor Green

foreach ($size in $sizes) {
    $outputFile = "$outputDir/icon-${size}x${size}.png"
    
    # Commande ImageMagick pour convertir et redimensionner
    # Si vous avez un SVG, utilisez :
    # magick convert -background none -resize ${size}x${size} $sourceLogo $outputFile
    
    # Si vous avez un PNG source, utilisez :
    # magick convert -resize ${size}x${size} "source.png" $outputFile
    
    Write-Host "Généré: icon-${size}x${size}.png" -ForegroundColor Cyan
}

Write-Host "`nIcônes générées avec succès!" -ForegroundColor Green
Write-Host "Note: Assurez-vous d'avoir ImageMagick installé pour exécuter ce script." -ForegroundColor Yellow
Write-Host "Alternative: Utilisez un service en ligne comme https://www.pwabuilder.com/imageGenerator" -ForegroundColor Yellow
