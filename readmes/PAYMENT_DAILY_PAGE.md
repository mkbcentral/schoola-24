# 💳 Page de Paiements Quotidiens - Documentation

## 📋 Vue d'ensemble

La **Page de Paiements Quotidiens** (`PaymentDailyPage`) est une interface moderne et élégante permettant de :
- Visualiser tous les paiements effectués par jour
- Filtrer les paiements par date
- Créer de nouveaux paiements via un modal sophistiqué
- Rechercher des élèves en temps réel
- Consulter l'historique des paiements par élève

## 🎨 Caractéristiques principales

### 1. **Tableau de bord des statistiques quotidiennes**
- 📊 Total des paiements du jour
- 💰 Montant total collecté
- ✅ Nombre de paiements validés
- ⏳ Nombre de paiements en attente

### 2. **Filtrage par date**
- Sélection de date personnalisée
- Boutons rapides : "Aujourd'hui" et "Hier"
- Mise à jour automatique des statistiques

### 3. **Modal de nouveau paiement**

#### Partie Gauche - Formulaire
- 🔍 **Recherche d'élève** : Recherche en temps réel avec dropdown
- 👤 **Informations élève** : Affichage élégant des détails de l'élève sélectionné
- 📝 **Formulaire** :
  - Sélection de la catégorie de frais
  - Choix du mois
  - Option pour marquer comme payé immédiatement
  - Validation avec gestion des dettes

#### Partie Droite - Historique
- 📚 Liste complète des paiements passés de l'élève
- 🏷️ Badges de statut (Payé / En attente)
- 💵 Montants et devises
- 📅 Dates de création

### 4. **Liste des paiements**
- 📋 Tableau responsive avec toutes les informations
- 🎨 Design moderne avec avatars et badges colorés
- ⚡ Actions rapides :
  - Valider un paiement
  - Supprimer un paiement non validé
- 📄 Pagination automatique

## 🛠️ Architecture technique

### Composant Livewire

**Fichier** : `app/Livewire/Financial/Payment/PaymentDailyPage.php`

#### Propriétés principales

```php
// Filtres
public $selectedDate;           // Date sélectionnée
public $startDate;              // Date de début
public $endDate;                // Date de fin

// Modal
public $showPaymentModal;       // Affichage du modal

// Recherche élève
public $studentSearch;          // Terme de recherche
public $searchResults;          // Résultats de la recherche
public $showSearchDropdown;     // Affichage dropdown

// Élève sélectionné
public $selectedStudent;        // Infos élève
public $selectedRegistrationId; // ID inscription
public $studentPaymentHistory;  // Historique paiements

// Formulaire
public $categoryFeeId;          // Catégorie de frais
public $selectedMonth;          // Mois sélectionné
public $isPaid;                 // Statut payé
```

#### Méthodes principales

| Méthode | Description |
|---------|-------------|
| `updatedStudentSearch()` | Recherche en temps réel des élèves |
| `selectStudent($id)` | Sélectionne un élève et charge son historique |
| `loadStudentPaymentHistory()` | Charge l'historique des paiements |
| `openPaymentModal()` | Ouvre le modal de nouveau paiement |
| `closePaymentModal()` | Ferme le modal et réinitialise |
| `savePayment()` | Enregistre le nouveau paiement |
| `loadDailyStats()` | Charge les statistiques du jour |
| `markAsPaid($id)` | Valide un paiement |
| `deletePayment($id)` | Supprime un paiement non validé |

### Services utilisés

#### 1. **StudentSearchService**
Recherche d'élèves par nom ou code

```php
$this->studentSearchService->search($query);
```

#### 2. **PaymentHistoryService**
Récupération de l'historique des paiements

```php
$this->paymentHistoryService->getStudentPaymentHistory($registrationId);
```

#### 3. **StudentDebtTrackerService**
Gestion des paiements avec vérification des dettes

```php
$this->debtTrackerService->payForMonth(
    registrationId: $registrationId,
    categoryFeeId: $categoryFeeId,
    targetMonth: $month,
    paymentData: ['is_paid' => $isPaid]
);
```

## 📁 Structure des fichiers

```
app/
├── Livewire/
│   └── Financial/
│       └── Payment/
│           └── PaymentDailyPage.php
├── Services/
│   ├── Student/
│   │   ├── StudentSearchService.php
│   │   └── StudentDebtTrackerService.php
│   └── Payment/
│       └── PaymentHistoryService.php
└── Models/
    ├── Payment.php
    ├── Registration.php
    └── CategoryFee.php

resources/
└── views/
    └── livewire/
        └── financial/
            └── payment/
                └── payment-daily-page.blade.php

routes/
└── web.php
```

## 🚀 Utilisation

### Accès à la page

**URL** : `/payment/daily`  
**Route** : `payment.daily`

```php
// Lien dans Blade
<a href="{{ route('payment.daily') }}">Paiements du jour</a>

// Redirection dans un controller
return redirect()->route('payment.daily');
```

### Exemples d'utilisation

#### 1. Créer un nouveau paiement

```
1. Cliquer sur "Nouveau Paiement"
2. Rechercher l'élève dans la barre de recherche
3. Sélectionner l'élève dans le dropdown
4. Choisir la catégorie de frais
5. Sélectionner le mois
6. Cocher "Marquer comme payé" si nécessaire
7. Cliquer sur "Enregistrer le paiement"
```

#### 2. Consulter l'historique

```
1. Dans le modal, rechercher et sélectionner un élève
2. L'historique s'affiche automatiquement à droite
3. Visualiser tous les paiements passés avec statuts
```

#### 3. Valider un paiement

```
1. Trouver le paiement dans la liste
2. Cliquer sur l'icône de validation (✓)
3. Confirmer l'action
4. Le statut passe à "Payé"
```

#### 4. Filtrer par date

```
1. Cliquer sur le champ de date
2. Sélectionner une date
   OU
   Cliquer sur "Aujourd'hui" / "Hier"
3. La liste et les statistiques se mettent à jour automatiquement
```

## 🎨 Design et UX

### Palette de couleurs

- **Bleu/Violet** : Actions principales, en-têtes
- **Vert** : Paiements validés, succès
- **Orange** : Paiements en attente, avertissements
- **Rouge** : Suppressions, erreurs
- **Gris** : Arrière-plans, textes secondaires

### Animations

- ✨ **fadeIn** : Apparition du modal (0.2s)
- ✨ **slideUp** : Animation du contenu du modal (0.3s)
- ✨ **Transitions** : Tous les hovers et changements d'état (0.2s)

### Responsive Design

- 📱 **Mobile** : Colonnes empilées, boutons pleine largeur
- 💻 **Tablet** : Grille adaptative 2 colonnes
- 🖥️ **Desktop** : Grille complète avec modal large

## 🔒 Sécurité et Validation

### Règles de validation

```php
protected $rules = [
    'selectedRegistrationId' => 'required|exists:registrations,id',
    'categoryFeeId' => 'required|exists:category_fees,id',
    'selectedMonth' => 'required|integer|min:1|max:12',
];
```

### Permissions

- ❌ **Impossible de supprimer** un paiement déjà validé
- ✅ **Vérification des dettes** avant création de paiement
- 🔐 **Authentification** requise (middleware auth)

## 📊 Statistiques

Les statistiques sont calculées en temps réel :

```php
$dailyStats = [
    'total_payments' => count($payments),
    'total_amount' => sum($payments->amount),
    'paid_count' => count($payments->where('is_paid', true)),
    'pending_count' => count($payments->where('is_paid', false)),
];
```

## 🐛 Gestion des erreurs

### Messages d'erreur

- 🔴 Élève non trouvé
- 🔴 Catégorie de frais non sélectionnée
- 🔴 Mois non sélectionné
- 🔴 Dette détectée (mois précédents impayés)
- 🔴 Paiement non trouvé
- 🔴 Impossible de supprimer un paiement validé

### Affichage des erreurs

```blade
@error('payment')
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
        <p class="text-sm text-red-600 flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $message }}
        </p>
    </div>
@enderror
```

## 🔄 Événements Livewire

### Événements écoutés

```php
protected $listeners = [
    'refreshPayments' => '$refresh',
    'paymentCreated' => 'handlePaymentCreated',
];
```

### Événements émis

```php
// Notification de succès
$this->dispatch('alert', [
    'type' => 'success',
    'message' => 'Paiement enregistré avec succès'
]);

// Rafraîchissement
$this->dispatch('refreshPayments');
```

## 🌐 Dark Mode

La page supporte automatiquement le mode sombre avec :
- Classes Tailwind `dark:` pour tous les éléments
- Contrastes adaptés pour la lisibilité
- Transitions fluides entre les modes

## 📈 Performance

### Optimisations

- ✅ **Lazy Loading** de la route
- ✅ **Pagination** des paiements (20 par page)
- ✅ **Eager Loading** des relations (with())
- ✅ **Recherche limitée** (minimum 2 caractères)
- ✅ **Dropdown auto-hide** quand non utilisé

### Requêtes optimisées

```php
Payment::with([
    'registration.student',
    'registration.classRoom.option',
    'scolarFee.categoryFee',
    'user'
])->whereDate('created_at', $date)
  ->orderBy('created_at', 'desc')
  ->paginate(20);
```

## 🎯 Points clés

1. ✨ **Interface moderne** avec animations et design élégant
2. 🚀 **Recherche en temps réel** pour une expérience fluide
3. 📊 **Statistiques visuelles** pour un aperçu rapide
4. 🔍 **Historique complet** pour chaque élève
5. ✅ **Validation automatique** des dettes
6. 📱 **Responsive** sur tous les écrans
7. 🌓 **Dark mode** intégré
8. ⚡ **Performance optimisée** avec eager loading

## 🔮 Évolutions futures possibles

- 📧 Notifications SMS/Email après paiement
- 📊 Graphiques de tendance des paiements
- 💳 Intégration de paiement en ligne
- 📄 Export PDF des paiements du jour
- 🔔 Alertes pour paiements en retard
- 📈 Statistiques avancées par période

---

**Dernière mise à jour** : Janvier 2026  
**Version** : 1.0.0  
**Compatibilité** : Laravel 11, Livewire 3, Tailwind CSS 3
