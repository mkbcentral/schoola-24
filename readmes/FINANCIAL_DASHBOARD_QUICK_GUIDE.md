# Guide Rapide - Dashboard Financier

## 📊 Accès rapide

```
URL: http://votre-domaine/finance/dashboard
Route: finance.dashboard
```

## ✨ Fonctionnalités principales

### 1. **Vue d'ensemble**

-   ✅ Recettes totales (Paiements Minerval) en USD et CDF
-   ✅ Dépenses totales sur frais en USD et CDF
-   ✅ Solde automatique (Recettes - Dépenses)

### 2. **Filtres disponibles**

-   📅 **Mois** : Voir les données d'un mois spécifique
-   📆 **Date** : Voir les données d'une date précise
-   🏷️ **Catégorie** : Changer la catégorie de frais (Minerval par défaut)
-   🔄 **Réinitialiser** : Retour aux filtres par défaut

### 3. **Graphiques interactifs**

-   📈 **Évolution USD** : Recettes, dépenses et solde mensuels en USD
-   📈 **Évolution CDF** : Recettes, dépenses et solde mensuels en CDF
-   📊 **Comparaison annuelle** : Barres comparatives recettes vs dépenses

## 🚀 Utilisation

### Exemple 1 : Voir les finances du mois actuel

1. Accéder au dashboard : `/finance/dashboard`
2. Le mois actuel est sélectionné par défaut
3. Consulter les cartes statistiques et graphiques

### Exemple 2 : Analyser un mois spécifique

1. Sélectionner le mois dans le filtre "Mois"
2. Les données se rechargent automatiquement
3. Observer les changements dans les graphiques

### Exemple 3 : Voir les finances d'une date précise

1. Choisir une date dans le filtre "Date spécifique"
2. Les données se mettent à jour
3. Idéal pour un rapport quotidien

### Exemple 4 : Changer de catégorie

1. Sélectionner une catégorie dans le filtre "Catégorie"
2. Les recettes et dépenses se recalculent pour cette catégorie
3. Les graphiques affichent l'évolution de cette catégorie

## 🎨 Interface

### Cartes de statistiques

-   **Carte verte** : Recettes (icône pièce de monnaie)
-   **Carte rouge** : Dépenses (icône portefeuille)
-   **Carte bleue/orange** : Solde (icône calculatrice)
    -   Bleue = Solde positif
    -   Orange = Solde négatif (attention !)

### Graphiques

-   **Ligne verte** : Recettes
-   **Ligne rouge** : Dépenses
-   **Ligne bleue pointillée** : Solde

## ⚙️ Configuration

### Catégorie par défaut (Minerval)

Le système utilise automatiquement la catégorie marquée avec `is_for_dash = true`.

Pour vérifier/modifier dans la base de données :

```sql
SELECT * FROM category_fees WHERE is_for_dash = 1;
```

## 📱 Responsive

Le dashboard s'adapte automatiquement aux écrans :

-   💻 Desktop : Vue complète avec tous les graphiques
-   📱 Mobile : Vue empilée pour une meilleure lisibilité

## 🔍 Détails techniques

### Sources de données

-   **Recettes** : Table `payments` (paiements payés uniquement)
-   **Dépenses** : Table `expense_fees`
-   **Année scolaire** : Utilise l'année scolaire active par défaut

### Devises supportées

-   💵 USD (Dollar américain)
-   🪙 CDF (Franc congolais)

## ⚠️ Prérequis

Pour que le dashboard fonctionne correctement :

1. ✅ Au moins une `CategoryFee` avec `is_for_dash = true`
2. ✅ Une année scolaire active (SchoolYear)
3. ✅ Données dans les tables `payments` et `expense_fees`

## 🐛 Résolution de problèmes

### Le dashboard est vide

-   Vérifier qu'il y a des données pour le mois/date sélectionné
-   Vérifier qu'une catégorie avec `is_for_dash = true` existe
-   Vérifier l'année scolaire active

### Les graphiques ne s'affichent pas

-   Ouvrir la console du navigateur (F12) pour voir les erreurs JavaScript
-   Vérifier que Chart.js est bien chargé (CDN)

### Erreur 500

-   Consulter les logs Laravel : `storage/logs/laravel.log`
-   Vérifier les permissions sur le dossier storage

## 📚 Documentation complète

Pour plus de détails techniques, consulter :

-   `readmes/FINANCIAL_DASHBOARD.md` : Documentation complète
-   Code source : `app/Services/FinancialDashboardService.php`

## 🎯 Cas d'usage

### Pour le Directeur

-   Vue rapide de la santé financière de l'école
-   Identifier les mois avec déficit
-   Planifier le budget

### Pour le Comptable

-   Suivi mensuel des recettes et dépenses
-   Rapprochement des comptes
-   Préparation des rapports financiers

### Pour le Gestionnaire

-   Analyse des tendances financières
-   Identification des périodes problématiques
-   Prise de décisions basées sur les données

## 💡 Astuces

1. **Comparaison mensuelle** : Utilisez les graphiques pour comparer les performances mois par mois
2. **Alertes visuelles** : La carte solde change de couleur selon le résultat (bleu = positif, orange = négatif)
3. **Export visuel** : Utilisez la capture d'écran pour partager les rapports
4. **Filtres multiples** : Combinez date ET catégorie pour des analyses précises

## 🚀 Évolutions futures

-   📄 Export PDF/Excel des statistiques
-   📧 Notifications automatiques en cas de déficit
-   📊 Comparaison multi-années
-   🤖 Prévisions basées sur l'IA
-   📱 Application mobile dédiée
