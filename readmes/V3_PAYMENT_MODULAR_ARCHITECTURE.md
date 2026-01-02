# Architecture Modulaire V3 - Gestion des Paiements

## 📐 Vue d'ensemble

La page de gestion des paiements V3 a été refactorisée pour adopter une architecture modulaire basée sur des composants Livewire autonomes et réutilisables.

## 🏗️ Structure des composants

### 1. **PaymentManagementPage** (Composant Parent)
**Fichier** : `app/Livewire/Application/V3/Payment/PaymentManagementPage.php`

**Responsabilités** :
- Gestion de la recherche d'élèves
- Coordination des composants enfants
- Communication entre le formulaire et la liste via événements Livewire

**Propriétés principales** :
```php
public $search = '';
public $selectedRegistrationId = null;
public $registration = null;
public $studentInfo = [];
```

**Événements émis** :
- `studentSelected` - Quand un élève est sélectionné
- `studentReset` - Quand la sélection est réinitialisée

---

### 2. **PaymentForm** (Composant Formulaire)
**Fichier** : `app/Livewire/Application/V3/Payment/PaymentForm.php`

**Responsabilités** :
- Affichage et gestion du formulaire de paiement
- Création et modification de paiements
- Validation des données
- Auto-chargement des frais scolaires

**Propriétés principales** :
```php
public $registrationId = null;
public $categoryFeeId = '';
public $month = '';
public $createdAt = '';
public $isPaid = false;
public $selectedFeeInfo = null;
```

**Événements écoutés** :
- `studentSelected` - Réception de l'élève sélectionné
- `editPayment` - Chargement d'un paiement à éditer

**Événements émis** :
- `paymentSaved` - Après création/modification réussie

**Actions utilisées** :
- `CreatePaymentAction::execute()`
- `UpdatePaymentAction::execute()`

---

### 3. **PaymentList** (Composant Liste)
**Fichier** : `app/Livewire/Application/V3/Payment/PaymentList.php`

**Responsabilités** :
- Affichage de la liste des paiements
- Filtrage par statut (Tous/Payés/Non payés)
- Actions sur les paiements (Valider, Modifier, Supprimer)

**Propriétés principales** :
```php
public $filterStatus = 'all';
public $selectedRegistrationId = null;
public $payments = [];
```

**Événements écoutés** :
- `paymentSaved` - Recharge la liste après sauvegarde
- `studentSelected` - Filtre par élève sélectionné
- `studentReset` - Réinitialise le filtre élève

**Événements émis** :
- `editPayment` - Demande d'édition d'un paiement

**Actions utilisées** :
- `DeletePaymentAction::execute()`

---

## 🔄 Flux de communication

### Création d'un paiement
```
PaymentManagementPage (recherche élève)
    ↓ dispatch('studentSelected')
PaymentForm (reçoit l'élève)
    ↓ save()
CreatePaymentAction
    ↓ dispatch('paymentSaved')
PaymentList (recharge)
```

### Édition d'un paiement
```
PaymentList (clic "Modifier")
    ↓ dispatch('editPayment', paymentId)
PaymentForm (charge le paiement)
    ↓ save()
UpdatePaymentAction
    ↓ dispatch('paymentSaved')
PaymentList (recharge)
```

### Suppression d'un paiement
```
PaymentList (clic "Supprimer")
    ↓ deletePayment()
DeletePaymentAction
    ↓ loadPayments()
PaymentList (recharge)
```

---

## 🎯 Avantages de cette architecture

### ✅ Séparation des responsabilités
- Chaque composant a un rôle unique et bien défini
- Facilite la maintenance et le débogage

### ✅ Réutilisabilité
- `PaymentForm` peut être utilisé dans d'autres contextes
- `PaymentList` peut être intégré ailleurs (ex: page élève)

### ✅ Testabilité
- Chaque composant peut être testé indépendamment
- Isolation des dépendances

### ✅ Communication événementielle
- Couplage faible entre composants
- Flexibilité pour ajouter de nouveaux écouteurs

### ✅ Performance
- Chaque composant ne se recharge que si nécessaire
- Optimisation des requêtes

---

## 📝 Patterns utilisés

### 1. **Actions Pattern**
```php
// Au lieu de logique métier dans le composant
$this->createPaymentAction->execute($data);
```

### 2. **Computed Properties**
```php
public function getCategoryFeesProperty()
{
    return CategoryFee::where(...)->get();
}
// Usage: $this->categoryFees
```

### 3. **Event-Driven Communication**
```php
// Émission
$this->dispatch('eventName', param: $value);

// Écoute
#[On('eventName')]
public function handleEvent($param) { }
```

### 4. **Helper Methods**
```php
private function notifySuccess(string $message): void
{
    $this->dispatch('notification', [
        'type' => 'success',
        'message' => $message
    ]);
}
```

---

## 🔧 Utilisation

### Dans une vue Blade
```blade
{{-- Composant complet --}}
@livewire('application.v3.payment.payment-management-page')

{{-- Formulaire seul --}}
@livewire('application.v3.payment.payment-form')

{{-- Liste seule --}}
@livewire('application.v3.payment.payment-list')
```

### Keys dynamiques
```blade
@livewire('application.v3.payment.payment-form', 
    key('payment-form-' . $registrationId))
```

---

## 🛠️ Extension future

Pour ajouter une nouvelle fonctionnalité :

1. **Créer un événement** dans le composant source
2. **Écouter l'événement** avec `#[On('eventName')]`
3. **Traiter l'événement** dans le composant cible
4. **Émettre un événement de réponse** si nécessaire

### Exemple : Ajout d'un historique de paiements
```php
// Dans PaymentList
$this->dispatch('paymentSelected', paymentId: $id);

// Nouveau composant PaymentHistory
#[On('paymentSelected')]
public function showPaymentHistory($paymentId) {
    // Logique...
}
```

---

## 📂 Fichiers de l'architecture

```
app/Livewire/Application/V3/Payment/
├── PaymentManagementPage.php    # Orchestrateur principal
├── PaymentForm.php               # Formulaire de paiement
└── PaymentList.php               # Liste des paiements

resources/views/livewire/application/v3/payment/
├── payment-management-page.blade.php
├── payment-form.blade.php
└── payment-list.blade.php

app/Actions/Payment/
├── CreatePaymentAction.php
├── UpdatePaymentAction.php
└── DeletePaymentAction.php
```

---

## 🎨 Bonnes pratiques appliquées

1. ✅ **Constantes** pour les valeurs magiques
2. ✅ **Type hints** stricts sur toutes les méthodes
3. ✅ **Extraction de méthodes** pour la lisibilité
4. ✅ **Documentation** complète des méthodes
5. ✅ **Gestion d'erreurs** centralisée
6. ✅ **Événements nommés** de façon explicite
7. ✅ **Keys dynamiques** pour Livewire
8. ✅ **Validation** des données avant traitement

---

## 🚀 Performance

### Optimisations appliquées :
- Chargement lazy des relations Eloquent
- Limitation du nombre de résultats (50 max)
- Rechargement ciblé des composants
- Eager loading des relations nécessaires

### Requêtes optimisées :
```php
Payment::with([
    'registration.student',
    'registration.classRoom.option.section',
    'scolarFee.categoryFee',
    'rate',
    'user'
])->latest()->limit(50)->get();
```

---

## 📚 Ressources

- [Documentation Livewire 3](https://livewire.laravel.com/docs)
- [Actions Pattern](https://laravel.com/docs/actions)
- [Event-Driven Architecture](https://martinfowler.com/articles/201701-event-driven.html)

---

**Dernière mise à jour** : 30 décembre 2025
**Version** : 3.0.0
**Auteur** : Architecture V3 - Système de paiement modulaire
