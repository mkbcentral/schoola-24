# 🎨 Guide des Composants de Rapports

## 📦 Composants Disponibles

### 1. `<x-report.filter-card>`

**Usage**: Container pour les filtres de rapports

```blade
<x-report.filter-card title="Mes Filtres">
    <div class="col-md-6">
        <label class="filter-label">Date</label>
        <input type="date" class="filter-input">
    </div>
    <div class="col-md-6">
        <label class="filter-label">Catégorie</label>
        <select class="filter-select">
            <option>Option 1</option>
        </select>
    </div>
</x-report.filter-card>
```

**Props**:

-   `title` (optionnel): Titre de la carte (défaut: "Filtres & Paramètres")

**Classes CSS disponibles**:

-   `.filter-label`: Pour les labels
-   `.filter-input`: Pour les inputs
-   `.filter-select`: Pour les selects

---

### 2. `<x-report.summary-card>`

**Usage**: Container pour le résumé financier

```blade
<x-report.summary-card title="Résumé">
    <!-- Vos items de résumé -->
</x-report.summary-card>
```

**Props**:

-   `title` (optionnel): Titre de la carte (défaut: "Résumé Financier")

---

### 3. `<x-report.summary-item>`

**Usage**: Item de résumé individuel

```blade
<x-report.summary-item
    label="Total Paiements"
    value="150"
    type="total"
    badge="25 paiements" />
```

**Props**:

-   `label` (requis): Label du résumé
-   `value` (requis): Valeur à afficher
-   `type` (optionnel): Type pour les couleurs
    -   `total`: Couleur primaire (bleu)
    -   `usd`: Vert
    -   `cdf`: Rouge
    -   `eur`: Bleu clair
-   `badge` (optionnel): Badge à afficher (ex: nombre de paiements)

---

### 4. `<x-report.action-button>`

**Usage**: Boutons d'action pour les rapports

```blade
<!-- Bouton Email -->
<x-report.action-button
    type="email"
    label="Envoyer par Email"
    data-bs-toggle="modal"
    data-bs-target="#emailModal" />

<!-- Bouton Téléchargement -->
<x-report.action-button
    type="download"
    label="Télécharger"
    href="{{ route('report.pdf') }}" />

<!-- Bouton Aperçu -->
<x-report.action-button
    type="preview"
    label="Aperçu"
    href="{{ route('report.preview') }}"
    target="_blank" />
```

**Props**:

-   `type` (optionnel): Type de bouton
    -   `email`: Bouton vert pour envoyer par email
    -   `download`: Bouton noir pour télécharger
    -   `preview`: Bouton blanc avec bordure pour aperçu
-   `label` (requis): Texte du bouton
-   `icon` (optionnel): Icône Bootstrap (défini automatiquement selon le type)
-   `href` (optionnel): Lien de destination
-   `target` (optionnel): Target du lien

**Icônes par défaut**:

-   `email`: `bi-envelope`
-   `download`: `bi-download`
-   `preview`: `bi-eye`

---

### 5. `<x-report.alert>`

**Usage**: Afficher des messages d'alerte

```blade
<!-- Alerte d'erreur -->
<x-report.alert type="error" title="Erreur">
    Le rapport ne peut pas être généré.
</x-report.alert>

<!-- Alerte d'avertissement -->
<x-report.alert type="warning" title="Attention">
    Aucune donnée pour la période sélectionnée.
</x-report.alert>

<!-- Alerte d'information -->
<x-report.alert type="info">
    Le rapport est en cours de génération...
</x-report.alert>
```

**Props**:

-   `type` (optionnel): Type d'alerte (défaut: "info")
    -   `error`: Rouge
    -   `warning`: Jaune
    -   `info`: Bleu
-   `title` (optionnel): Titre de l'alerte

---

## 🎨 Classes CSS Disponibles

### Container

```scss
.report-container // Container principal avec background
```

### Cards

```scss
.report-filter-card       // Carte de filtres
.report-summary-card      // Carte de résumé
.report-details-table     // Table de détails
```

### Éléments de filtres

```scss
.filter-header   // Titre de section
.filter-label    // Label de champ
.filter-input    // Input de formulaire
.filter-select   // Select de formulaire
```

### Éléments de résumé

```scss
.summary-header           // Titre de section
.summary-item            // Item de résumé
  .summary-item-total    // Couleur primaire
  .summary-item-usd      // Couleur verte
  .summary-item-cdf      // Couleur rouge
  .summary-item-eur      // Couleur bleue
.summary-label           // Label du résumé
.summary-value           // Valeur du résumé
.summary-badge           // Badge
.summary-footer          // Pied de page
```

### Boutons d'action

```scss
.report-action-btn          // Bouton de base
  .report-action-email      // Bouton email (vert)
  .report-action-download   // Bouton télécharger (noir)
  .report-action-preview    // Bouton aperçu (blanc)
```

### Table de détails

```scss
.details-header    // Titre de section
.table-footer      // Pied de table
  .footer-grid     // Grille 3 colonnes
  .footer-item     // Item de pied
  .footer-label    // Label de pied
  .footer-value    // Valeur de pied
```

### Alertes

```scss
.report-alert               // Alerte de base
  .report-alert-error      // Alerte d'erreur (rouge)
  .report-alert-warning    // Alerte d'avertissement (jaune)
  .report-alert-info       // Alerte d'information (bleu)
```

---

## 📱 Support Responsive

Tous les composants sont responsive. Breakpoint principal: **768px**

```scss
// Mobile (< 768px)
- Cards: padding réduit
- Footer grid: 1 colonne
- Boutons: pleine largeur
```

---

## 🌓 Support Dark Mode

Tous les composants utilisent les variables CSS et s'adaptent automatiquement au thème:

```scss
// Variables utilisées
--text-primary
--text-secondary
--text-muted
--bg-secondary
--border-color
--card-bg
--input-bg
--input-focus-border
--color-primary
--color-success
--color-danger
--color-info
```

---

## 💡 Exemple Complet

Voir le fichier: `resources/views/examples/report-components-example.blade.php`

---

## 🔄 Migration depuis Styles Inline

### Avant (❌ Styles inline)

```blade
<div style="background: white; border: 1px solid #e1e4e8; padding: 1.75rem;">
    <h6 style="color: #1a1f36; font-weight: 600;">Filtres</h6>
    <select style="width: 100%; padding: 0.6rem; border: 1px solid #ddd;">
        <option>Option 1</option>
    </select>
</div>
```

### Après (✅ Composant)

```blade
<x-report.filter-card title="Filtres">
    <div class="col-12">
        <label class="filter-label">Type</label>
        <select class="filter-select">
            <option>Option 1</option>
        </select>
    </div>
</x-report.filter-card>
```

**Avantages**:

-   ✅ Code plus court et lisible
-   ✅ Support du dark mode automatique
-   ✅ Responsive par défaut
-   ✅ Maintenabilité améliorée
-   ✅ Cohérence visuelle garantie

---

## 📚 Ressources

-   Documentation Blade Components: [Laravel Blade](https://laravel.com/docs/blade#components)
-   Fichier SCSS: `resources/sass/components/_reports.scss`
-   Exemples: `resources/views/examples/report-components-example.blade.php`
-   Corrections: `readmes/CSS_HTML_CORRECTIONS_REPORT.md`

---

**Dernière mise à jour**: 9 Décembre 2025
