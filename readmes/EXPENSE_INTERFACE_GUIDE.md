# Nouvelle Interface de Gestion des Dépenses

## Vue d'ensemble

La nouvelle interface de gestion des dépenses offre une expérience utilisateur moderne, fluide et intuitive pour gérer à la fois les **Dépenses sur Frais** et les **Autres Dépenses** depuis une seule page unifiée.

## Accès

**URL :** `/expense/manage`  
**Route :** `route('expense.manage')`  
**Composant :** `App\Livewire\Application\Finance\Expense\ExpenseManagementPage`

## Fonctionnalités principales

### 1. Switch entre types de dépenses

Un système de boutons permet de basculer instantanément entre :

-   **Dépenses sur Frais** : Dépenses liées aux frais scolaires (inscription, scolarité, etc.)
-   **Autres Dépenses** : Dépenses diverses (fournitures, maintenance, etc.)

Le changement de type :

-   ✅ Recharge automatiquement la liste appropriée
-   ✅ Ajuste les filtres disponibles
-   ✅ Met à jour les statistiques
-   ✅ Adapte le formulaire de création/édition

### 2. Statistiques en temps réel

Quatre cartes de statistiques affichent :

-   **Total USD** : Somme totale des dépenses en dollars
-   **Total CDF** : Somme totale des dépenses en francs congolais
-   **Total (USD)** : Total converti en USD selon le taux actuel
-   **Nombre** : Nombre total de dépenses filtrées

Les statistiques se mettent à jour automatiquement selon les filtres appliqués.

### 3. Filtres avancés

#### Filtres disponibles :

-   **Période prédéfinie** :

    -   Aujourd'hui
    -   Cette semaine
    -   Ce mois
    -   Mois dernier
    -   3 derniers mois
    -   6 derniers mois
    -   Cette année

-   **Mois** : Sélection d'un mois spécifique (Janvier à Décembre)

-   **Devise** : USD ($) ou CDF (FC)

-   **Catégorie de dépense** : Toutes les catégories configurées

-   **Type spécifique** :
    -   Pour dépenses sur frais : Type de frais
    -   Pour autres dépenses : Source de dépense

#### Bouton de réinitialisation

Un bouton "Réinitialiser" permet de supprimer tous les filtres en un clic.

### 4. Tableau des dépenses

Le tableau affiche :

-   **Date** : Date de création de la dépense
-   **Description** : Description détaillée
-   **Mois** : Mois concerné (badge coloré)
-   **Catégorie** : Catégorie de dépense (badge info)
-   **Type/Source** : Type de frais ou source selon le type de dépense
-   **Montant** : Montant avec devise et formatage automatique
-   **Actions** : Boutons Modifier et Supprimer

#### Pagination

-   15 dépenses par page
-   Pagination Bootstrap avec navigation complète

### 5. Modal de création/édition

Un modal moderne s'ouvre pour :

-   ✅ Créer une nouvelle dépense
-   ✅ Modifier une dépense existante

#### Champs du formulaire :

1. **Description** (textarea) : Description détaillée obligatoire
2. **Mois** (select) : Sélection du mois concerné
3. **Montant** (number) : Montant avec décimales
4. **Devise** (select) : USD ou CDF
5. **Catégorie de dépense** (select) : Obligatoire
6. **Type/Source** (select) :
    - Type de frais (pour dépenses sur frais)
    - Source de dépense (pour autres dépenses)

#### Validation

-   Validation en temps réel
-   Messages d'erreur clairs en français
-   Empêche la soumission si erreurs

### 6. Actions CRUD complètes

#### Créer

1. Cliquer sur "Nouvelle Dépense"
2. Remplir le formulaire
3. Cliquer sur "Enregistrer"
4. Message de confirmation

#### Modifier

1. Cliquer sur l'icône crayon dans la ligne
2. Le modal s'ouvre avec les données pré-remplies
3. Modifier les champs souhaités
4. Cliquer sur "Modifier"
5. Message de confirmation

#### Supprimer

1. Cliquer sur l'icône poubelle
2. Confirmer la suppression
3. Message de confirmation

### 7. Messages de feedback

Tous les messages de succès/erreur s'affichent en haut de la page avec :

-   ✅ Icône appropriée
-   ✅ Couleur contextuelle (vert pour succès, rouge pour erreur)
-   ✅ Bouton de fermeture

### 8. Taux de change

Un bandeau informatif en bas de page affiche :

```
🔄 Taux de change actuel : 1 USD = 2850 CDF
```

Mis à jour automatiquement depuis la base de données.

## Architecture technique

### Services utilisés

```php
// Services injectés
ExpenseServiceInterface       // Gestion des dépenses sur frais
OtherExpenseServiceInterface  // Gestion des autres dépenses
CurrencyExchangeServiceInterface // Conversion de devises
```

### DTOs utilisés

```php
ExpenseDTO              // Encapsulation des données de dépenses sur frais
OtherExpenseDTO         // Encapsulation des données d'autres dépenses
ExpenseFilterDTO        // Filtres pour dépenses sur frais
OtherExpenseFilterDTO   // Filtres pour autres dépenses
```

### Propriétés Livewire

```php
// Type et état
public string $expenseType = 'fee';  // 'fee' ou 'other'
public bool $showModal = false;
public bool $isEditing = false;

// Formulaire
public ?int $expenseId = null;
public string $description = '';
public string $month = '';
public float $amount = 0;
public string $currency = 'USD';
public int $categoryExpenseId = 0;
public int $categoryFeeId = 0;
public int $otherSourceExpenseId = 0;

// Filtres
public string $searchTerm = '';
public string $filterMonth = '';
public string $filterCurrency = '';
public int $filterCategoryExpense = 0;
public int $filterCategoryFee = 0;
public int $filterOtherSource = 0;
public string $filterPeriod = '';
public string $filterStartDate = '';
public string $filterEndDate = '';

// Messages
public string $message = '';
public string $messageType = 'success';
```

### Méthodes principales

```php
// Navigation
switchExpenseType(string $type)  // Basculer entre fee/other

// Modal
openCreateModal()                 // Ouvrir pour création
openEditModal(int $id)           // Ouvrir pour édition
closeModal()                     // Fermer le modal

// CRUD
save()                           // Sauvegarder (création ou édition)
delete(int $id)                  // Supprimer une dépense

// Filtres
resetFilters()                   // Réinitialiser tous les filtres
applyPeriodFilter(string $period) // Appliquer un filtre de période

// Data
getExpenses()                    // Obtenir les dépenses filtrées
getStatistics()                  // Obtenir les statistiques
```

## Guide d'utilisation

### Cas d'usage 1 : Créer une dépense sur frais

1. Accéder à `/expense/manage`
2. S'assurer que "Dépenses sur Frais" est sélectionné (bouton bleu)
3. Cliquer sur "Nouvelle Dépense"
4. Remplir le formulaire :
    - Description : "Achat de craies"
    - Mois : "Octobre"
    - Montant : 50
    - Devise : USD
    - Catégorie : "Fournitures"
    - Type de Frais : "Inscription"
5. Cliquer sur "Enregistrer"
6. Message de confirmation : "Dépense créée avec succès"

### Cas d'usage 2 : Basculer vers autres dépenses

1. Cliquer sur le bouton "Autres Dépenses"
2. La page se recharge avec :
    - Liste des autres dépenses
    - Statistiques mises à jour
    - Filtre "Source de dépense" au lieu de "Type de Frais"

### Cas d'usage 3 : Filtrer par période

1. Dans la section "Filtres", sélectionner "Ce mois" dans "Période"
2. La liste se filtre automatiquement
3. Les statistiques se recalculent
4. Pour réinitialiser, cliquer sur "Réinitialiser"

### Cas d'usage 4 : Modifier une dépense

1. Dans le tableau, trouver la dépense à modifier
2. Cliquer sur l'icône crayon (🖊️)
3. Le modal s'ouvre avec les données
4. Modifier les champs souhaités
5. Cliquer sur "Modifier"
6. Message : "Dépense modifiée avec succès"

### Cas d'usage 5 : Supprimer une dépense

1. Dans le tableau, trouver la dépense à supprimer
2. Cliquer sur l'icône poubelle (🗑️)
3. Confirmer dans la boîte de dialogue
4. Message : "Dépense supprimée avec succès"

### Cas d'usage 6 : Consulter les statistiques mensuelles

1. Dans "Filtres", sélectionner un mois spécifique
2. Les statistiques en haut s'actualisent pour ce mois
3. Le tableau montre uniquement les dépenses du mois
4. Les totaux USD/CDF sont calculés

## Design et ergonomie

### Couleurs utilisées

-   **Primaire (Bleu)** : `bg-primary` - Actions principales, en-têtes
-   **Info (Cyan)** : `bg-info` - Badges catégories
-   **Succès (Vert)** : `bg-success` - Total USD converti, messages succès
-   **Warning (Orange)** : `bg-warning` - Statistiques de nombre
-   **Secondaire (Gris)** : `bg-secondary` - Badges mois, boutons secondaires
-   **Danger (Rouge)** : `bg-danger` - Bouton supprimer, messages erreur

### Icônes Bootstrap

-   `bi-cash-stack` : Icône principale (dépenses)
-   `bi-receipt` : Dépenses sur frais
-   `bi-box-seam` : Autres dépenses
-   `bi-plus-circle` : Ajouter
-   `bi-pencil` : Modifier
-   `bi-trash` : Supprimer
-   `bi-funnel` : Filtres
-   `bi-currency-dollar` : USD
-   `bi-cash` : CDF
-   `bi-graph-up-arrow` : Total converti

### Responsive

L'interface est entièrement responsive :

-   **Desktop** : Statistiques en 4 colonnes, formulaire en 2 colonnes
-   **Tablette** : Statistiques en 2 colonnes, formulaire adaptatif
-   **Mobile** : Statistiques empilées, formulaire sur 1 colonne

## Performances

### Optimisations implémentées

1. **Lazy loading** : `->lazy()` sur la route
2. **Pagination** : 15 éléments par page (configurable)
3. **Cache** : Taux de change mis en cache 24h
4. **Requêtes optimisées** :
    - Eager loading des relations (categoryExpense, categoryFee, etc.)
    - Filtres appliqués au niveau SQL
5. **Live updates** : `wire:model.live` pour filtres instantanés

## Comparaison avec l'ancienne interface

| Fonctionnalité    | Ancienne version   | Nouvelle version        |
| ----------------- | ------------------ | ----------------------- |
| Pages séparées    | 2 pages distinctes | 1 page unifiée          |
| Switch type       | Navigation menu    | Bouton toggle           |
| Formulaires       | Pages dédiées      | Modal intégré           |
| Filtres           | Basiques           | Avancés avec périodes   |
| Statistiques      | Limitées           | Complètes multi-devises |
| Architecture      | Eloquent direct    | Services + DTOs         |
| Conversion devise | Manuelle           | Automatique             |
| Validation        | Contrôleur         | DTO + Service           |
| Design            | Bootstrap 4        | Bootstrap 5 moderne     |

## Avantages de la nouvelle interface

✅ **Expérience utilisateur améliorée** : Moins de clics, navigation fluide  
✅ **Performance** : Services optimisés avec cache  
✅ **Maintenabilité** : Architecture propre (Services, DTOs, Contracts)  
✅ **Évolutivité** : Facile d'ajouter de nouvelles fonctionnalités  
✅ **Testabilité** : Services testables unitairement  
✅ **Cohérence** : Design uniforme avec le reste de l'application  
✅ **Sécurité** : Validation stricte via DTOs  
✅ **Accessibilité** : ARIA labels, navigation au clavier

## Futures améliorations possibles

1. **Export** : Export Excel/PDF des dépenses filtrées
2. **Graphiques** : Visualisation des dépenses par catégorie/mois
3. **Recherche texte** : Recherche full-text dans les descriptions
4. **Tri** : Tri par colonne dans le tableau
5. **Actions groupées** : Suppression multiple, changement de catégorie
6. **Historique** : Audit trail des modifications
7. **Pièces jointes** : Upload de justificatifs
8. **Notifications** : Alertes pour dépenses importantes
9. **Budget** : Comparaison avec budget prévisionnel
10. **Permissions** : Gestion fine des permissions par rôle

## Dépendances

### Services requis

-   `ExpenseServiceInterface` + `ExpenseService`
-   `OtherExpenseServiceInterface` + `OtherExpenseService`
-   `CurrencyExchangeServiceInterface` + `CurrencyExchangeService`

### DTOs requis

-   `ExpenseDTO`
-   `OtherExpenseDTO`
-   `ExpenseFilterDTO`
-   `OtherExpenseFilterDTO`

### Modèles requis

-   `ExpenseFee`
-   `OtherExpense`
-   `CategoryExpense`
-   `CategoryFee`
-   `OtherSourceExpense`
-   `SchoolYear`
-   `Rate`

### Helpers requis

-   `app_format_number()` : Formatage des nombres
-   `school_id()` : ID de l'école active

## Support et maintenance

Pour toute question ou problème :

1. Consulter les logs : `storage/logs/laravel.log`
2. Vérifier les services sont enregistrés dans `AppServiceProvider`
3. Tester les méthodes de service dans Tinker
4. Vérifier les permissions utilisateur
5. S'assurer que les données de référence existent (catégories, sources)

---

**Version :** 1.0.0  
**Date :** 26 novembre 2025  
**Auteur :** GitHub Copilot
