# 📋 Service StudentDebtTrackerService - Documentation Complète

## 🎯 Objectif Général

Le service `StudentDebtTrackerService` est un **gestionnaire de dettes d'élèves** pour le système de paiement des frais scolaires. Il vérifie si un élève peut payer pour un mois donné en analysant s'il a des dettes sur les mois précédents.

**Cas d'Usage Principal :** Éviter qu'un élève paye le mois de NOVEMBRE alors qu'il a une dette sur OCTOBRE (respect de la chronologie).

---

## 📍 Localisation

```
Namespace: App\Services
Fichier: app/Services/StudentDebtTrackerService.php
Imports:
  - DateFormatHelper (conversion mois en nombres)
  - Registration, CategoryFee, Payment, Rate, SchoolYear, ScolarFee (Models)
  - Auth (utilisateur connecté)
```

---

## 🔧 Méthodes Publiques

### 1️⃣ `payForMonth()` - Enregistrer un Paiement

#### **Signature**

```php
public function payForMonth(
    int $registrationId,        // ID de l'inscription
    int $categoryFeeId,         // Catégorie de frais (ex: frais scolaires, frais inscription)
    string $targetMonth,        // Mois visé (ex: 'OCTOBRE')
    array $paymentData = []     // Données optionnelles
): array                        // Retourne ['success' => bool, 'message' => string]
```

#### **Flux Étape par Étape**

```
┌─────────────────────────────────────────────────────────────┐
│ ENTRÉE: Student veut payer pour NOVEMBRE                   │
│ - registrationId: 42                                        │
│ - categoryFeeId: 5                                          │
│ - targetMonth: 'NOVEMBRE'                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ ÉTAPE 1: Vérifier que la catégorie existe                  │
│ CategoryFee::find(5)                                        │
│ ✓ Trouvée ou ✗ Erreur: "Catégorie non trouvée"           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ ÉTAPE 2: Vérifier le TYPE de paiement                      │
│                                                             │
│ ┌─ Si is_paid_in_installment = TRUE (paiement par tranche) │
│ │  → Pas de contrôle de dette nécessaire                   │
│ │  → Passer directement au paiement                        │
│ │                                                           │
│ └─ Si is_paid_in_installment = FALSE (paiement par mois)  │
│    → Vérifier les dettes des mois précédents               │
│    → Appeler canPayForMonth() pour validation              │
└─────────────────────────────────────────────────────────────┘
                           ↓
                  ┌────────┴────────┐
                  ↓                 ↓
          PAIEMENT PAR TRANCHE  PAIEMENT PAR MOIS
          (skip check)         (faire check)
                  │                 │
                  └────────┬────────┘
                           ↓
    ┌──────────────────────────────────────────────┐
    │ ÉTAPE 3: Appeler canPayForMonth() si besoin  │
    │ Retour: ['can_pay' => bool, 'message' => str]
    │                                              │
    │ ✓ can_pay = true  → Continuer              │
    │ ✗ can_pay = false → Retourner erreur       │
    └──────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ ÉTAPE 4: Charger l'inscription avec ses relations          │
│ Registration::with(['classRoom', 'payments', 'student'])   │
│                 .find(42)                                   │
│ ✓ Trouvée → continuer                                      │
│ ✗ Non trouvée → Retourner "Inscription non trouvée"       │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ ÉTAPE 5: Trouver les frais scolaires pour cette classe     │
│ ScolarFee                                                   │
│  .where('category_fee_id', 5)       // Même catégorie     │
│  .where('class_room_id', 12)        // Même classe        │
│  .first()                                                  │
│ ✓ Trouvé → continuer                                       │
│ ✗ Non trouvé → Retourner "Frais scolaire non trouvé"     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ ÉTAPE 6: Créer le numéro de paiement UNIQUE               │
│ paymentNumber = 'PAY-' + uniqid() + '-' + userId          │
│ Ex: 'PAY-673b8e2c3f4a0-7'                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ ÉTAPE 7: Créer et enregistrer l'objet Payment              │
│                                                             │
│ $payment = new Payment()                                   │
│ $payment->payment_number = 'PAY-673b8e2c3f4a0-7'          │
│ $payment->registration_id = 42                             │
│ $payment->scolar_fee_id = 105                              │
│ $payment->month = 11  // 'NOVEMBRE' → 11                  │
│ $payment->rate_id = DEFAULT_RATE_ID()  // Taux de change  │
│ $payment->user_id = Auth::id()  // Utilisateur connecté   │
│ $payment->is_paid = false  // Pas encore payé (pending)   │
│ $payment->save()  // INSERT en base de données             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ RÉSULTAT: Retourner succès                                 │
│ ['success' => true, 'message' => 'Paiement enregistré']   │
└─────────────────────────────────────────────────────────────┘
```

#### **Exemples d'Exécution**

**✓ Cas Succès - Paiement par Tranche:**

```php
$service = new StudentDebtTrackerService();
$result = $service->payForMonth(
    registrationId: 42,
    categoryFeeId: 5,      // Paiement par tranche
    targetMonth: 'NOVEMBRE',
    paymentData: []
);

// Résultat:
// ['success' => true, 'message' => 'Paiement enregistré avec succès.']

// La base de données:
// INSERT INTO payments (payment_number, registration_id, scolar_fee_id, month, rate_id, user_id, is_paid)
// VALUES ('PAY-xyz-7', 42, 105, 11, 1, 7, 0)
```

**✗ Cas Erreur - Catégorie Non Trouvée:**

```php
$result = $service->payForMonth(42, 9999, 'NOVEMBRE');
// ['success' => false, 'message' => 'Catégorie de frais non trouvée.']
```

**✗ Cas Erreur - Élève a une Dette:**

```php
// L'élève n'a pas payé OCTOBRE
$result = $service->payForMonth(42, 5, 'NOVEMBRE');

// Si canPayForMonth() détecte une dette:
// ['success' => false, 'message' => 'L'élève a une dette sur le mois d'OCTOBRE...']
```

---

### 2️⃣ `canPayForMonth()` - Vérifier si Paiement Autorisé

#### **Signature**

```php
public function canPayForMonth(
    int $registrationId,    // ID inscription
    int $categoryFeeId,     // Catégorie de frais
    string $targetMonth     // Mois visé (ex: 'NOVEMBRE')
): array
// Retourne: [
//   'can_pay' => bool,
//   'first_unpaid_month' => string|null,
//   'message' => string
// ]
```

#### **Flux Étape par Étape**

```
┌──────────────────────────────────────────────────────────┐
│ ENTRÉE: Vérifier si l'élève peut payer NOVEMBRE         │
│ - registrationId: 42                                     │
│ - categoryFeeId: 5                                       │
│ - targetMonth: 'NOVEMBRE'                                │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 1: Normaliser le mois d'entrée                    │
│ 'NOVEMBRE' → strtoupper(trim()) → 'NOVEMBRE'            │
│ '09' → ltrim('0') → '9' → 'SEPTEMBRE'                   │
│ 'novembre' → 'NOVEMBRE'                                  │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 2: Valider le mois                                │
│ getMonthsNumber() = [                                    │
│   'SEPTEMBRE' => 9,                                      │
│   'OCTOBRE' => 10,                                       │
│   'NOVEMBRE' => 11,  ← Trouvé !                         │
│   'DECEMBRE' => 12,                                      │
│   'JANVIER' => 1,                                        │
│   ...                                                    │
│ ]                                                        │
│                                                          │
│ Si 'NOVEMBRE' pas dans la liste → Erreur               │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 3: Récupérer l'inscription complète               │
│ Registration::with(['payments.scolarFee'])              │
│             .where('id', 42)                            │
│             .where('school_year_id', active_year_id)    │
│             .first()                                    │
│                                                          │
│ Charge aussi les paiements et leurs frais associés      │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 4: Récupérer date d'inscription                  │
│ $inscriptionDate = $registration->created_at            │
│ Ex: 2024-09-15 14:30:00                                 │
│ Cela signifie: l'élève s'est inscrit en SEPTEMBRE      │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 5: Boucler sur tous les mois AVANT le mois visé  │
│                                                          │
│ Ordre chronologique:                                     │
│ SEPTEMBRE(9) → OCTOBRE(10) → NOVEMBRE(11)              │
│                                    ↑                    │
│                              targetMonth = 11            │
│                                                          │
│ Vérifier: 9, 10                                         │
│ STOP avant d'atteindre 11                               │
└──────────────────────────────────────────────────────────┘
                         ↓
    ┌────────────────────────────────────────────────┐
    │ POUR CHAQUE MOIS PRÉCÉDENT (9, 10)             │
    └────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 6: Vérifier si élève était inscrit ce mois       │
│                                                          │
│ Si mois_à_vérifier < mois_inscription                  │
│ → IGNORER (élève n'était pas encore inscrit)           │
│                                                          │
│ Exemple: inscription en SEPTEMBRE (9)                   │
│ - Vérifier JUILLET (7)? NON → continue                │
│ - Vérifier AOÛT (8)? NON → continue                    │
│ - Vérifier SEPTEMBRE (9)? OUI → vérifier paiement      │
│ - Vérifier OCTOBRE (10)? OUI → vérifier paiement       │
└──────────────────────────────────────────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 7: Vérifier si l'élève a PAYÉ ce mois            │
│                                                          │
│ Chercher dans $registration->payments:                 │
│ - Payment.category_fee_id == 5 (bonne catégorie)      │
│ - Payment.month == 9 (SEPTEMBRE)                       │
│ - Payment.is_paid == true (marqué comme payé)         │
│                                                          │
│ ✓ Trouvé → continue vers mois suivant                  │
│ ✗ Non trouvé → BLOCAGE: "Dette sur SEPTEMBRE"        │
└──────────────────────────────────────────────────────────┘
                         ↓
    ┌─────────────────────────────┬──────────────────────┐
    │ PAIEMENT TROUVÉ             │ PAIEMENT MANQUANT     │
    ├─────────────────────────────┼──────────────────────┤
    │ Continue boucle             │ Retourner erreur:    │
    │ Vérifier mois suivant (10)  │ can_pay = false      │
    │ (OCTOBRE)                   │ first_unpaid_month   │
    │                             │  = 'SEPTEMBRE'       │
    │                             │ message = "L'élève a │
    │                             │ une dette..."        │
    └─────────────────────────────┴──────────────────────┘
                         ↓
┌──────────────────────────────────────────────────────────┐
│ ÉTAPE 8: Tous les mois vérifiés OK                      │
│                                                          │
│ Boucle terminée sans blocage                            │
│ → Paiement AUTORISÉ                                     │
│                                                          │
│ Retourner:                                              │
│ [                                                        │
│   'can_pay' => true,                                    │
│   'first_unpaid_month' => null,                         │
│   'message' => 'Paiement autorisé.'                     │
│ ]                                                        │
└──────────────────────────────────────────────────────────┘
```

#### **Exemples d'Exécution**

**✓ Cas Succès - Tous les Mois Payés:**

```php
// État actuel:
// - Inscription: SEPTEMBRE 2024
// - SEPTEMBRE: PAYÉ ✓
// - OCTOBRE: PAYÉ ✓
// - Essaye de payer: NOVEMBRE

$result = $service->canPayForMonth(42, 5, 'NOVEMBRE');

// Résultat:
// [
//   'can_pay' => true,
//   'first_unpaid_month' => null,
//   'message' => 'Paiement autorisé.'
// ]
```

**✗ Cas Erreur - Mois Précédent Impayé:**

```php
// État actuel:
// - SEPTEMBRE: PAYÉ ✓
// - OCTOBRE: IMPAYÉ ✗
// - Essaye de payer: NOVEMBRE

$result = $service->canPayForMonth(42, 5, 'NOVEMBRE');

// Résultat:
// [
//   'can_pay' => false,
//   'first_unpaid_month' => 'OCTOBRE',
//   'message' => "L'élève a une dette sur le mois d'OCTOBRE. Veuillez régulariser avant de payer NOVEMBRE."
// ]
```

**✗ Cas Erreur - Mois Invalide:**

```php
$result = $service->canPayForMonth(42, 5, 'INVALID_MONTH');

// Résultat:
// [
//   'can_pay' => false,
//   'first_unpaid_month' => null,
//   'message' => 'Mois cible invalide.'
// ]
```

**✗ Cas Erreur - Inscription Non Trouvée:**

```php
$result = $service->canPayForMonth(9999, 5, 'NOVEMBRE');

// Résultat:
// [
//   'can_pay' => false,
//   'first_unpaid_month' => null,
//   'message' => 'Inscription non trouvée.'
// ]
```

---

## 🔐 Méthodes Privées

### 3️⃣ `getMonthNumber()` - Convertir Mois en Nombre

#### **Signature**

```php
private function getMonthNumber(string $monthLabel): ?int
```

#### **Fonction**

Convertit un label de mois français en son numéro:

-   `'JANVIER'` → `1`
-   `'FÉVRIER'` → `2`
-   `'SEPTEMBRE'` → `9`
-   `'NOVEMBRE'` → `11`

#### **Utilise**

`DateFormatHelper::getMonthsNumber()` qui retourne:

```php
[
    'SEPTEMBRE' => 9,
    'OCTOBRE' => 10,
    'NOVEMBRE' => 11,
    'DECEMBRE' => 12,
    'JANVIER' => 1,
    'FEVRIER' => 2,
    'MARS' => 3,
    'AVRIL' => 4,
    'MAI' => 5,
    'JUIN' => 6,
]
```

#### **Exemple**

```php
$monthNum = $this->getMonthNumber('NOVEMBRE');
// Retourne: 11

$monthNum = $this->getMonthNumber('INVALID');
// Retourne: null
```

---

## 🗄️ Modèles de Données

### **Model: Registration** (Inscription)

```php
{
  id: 42,
  code: 'REG-001',
  student_id: 5,
  class_room_id: 12,
  school_year_id: 3,
  created_at: '2024-09-15'  ← Date d'inscription
  payments: [            ← Relation chargée
    Payment, Payment, ...
  ],
  classRoom: ClassRoom,
  student: Student
}
```

### **Model: CategoryFee** (Catégorie Frais)

```php
{
  id: 5,
  name: 'Frais Scolaires',
  is_paid_in_installment: false  ← Important!
                                   // true = par tranche
                                   // false = par mois
  is_accessory: false,
  is_for_dash: true,
  currency: 'CDF'
}
```

### **Model: Payment** (Paiement)

```php
{
  id: 999,
  payment_number: 'PAY-673b8e2c3f4a0-7',
  registration_id: 42,
  scolar_fee_id: 105,
  month: 11,             ← Numéro du mois (1-12)
  rate_id: 1,            ← Taux de change
  user_id: 7,            ← Qui a enregistré le paiement
  is_paid: false,        ← Statut (enregistré vs réel paiement)
  created_at: '2024-11-10'
}
```

### **Model: ScolarFee** (Frais Scolaires)

```php
{
  id: 105,
  category_fee_id: 5,
  class_room_id: 12,
  amount: 50000,
  currency: 'CDF'
}
```

---

## 🔄 Flux de Communication Complet

### **Scénario: Élève Paie ses Frais**

```
┌────────────────────────────────────┐
│ Interface Utilisateur               │
│ Bouton: "Enregistrer Paiement"      │
│ - Élève: John (ID: 42)              │
│ - Mois: NOVEMBRE                    │
│ - Frais: Frais Scolaires (ID: 5)    │
└────────────────────────────────────┘
              ↓
┌────────────────────────────────────┐
│ Livewire Component (ou Controller)  │
│ NewPaymentPage                      │
│ Appelle:                            │
│ $service->payForMonth(              │
│   42, 5, 'NOVEMBRE', [...]         │
│ )                                   │
└────────────────────────────────────┘
              ↓
┌────────────────────────────────────┐
│ StudentDebtTrackerService           │
│ payForMonth() START                 │
│                                     │
│ ✓ Catégorie existe?                 │
│ ✓ Type = Par Mois?                  │
│ → Vérifier dettes: canPayForMonth() │
│    ├─ Mois valide?                 │
│    ├─ Inscription existe?           │
│    ├─ Date inscription ok?          │
│    └─ Tous mois précédents payés?  │
│ ✓ Inscription trouvée?              │
│ ✓ Frais trouvés?                    │
│ → Créer Payment (INSERT)            │
│ → Retourner succès                  │
└────────────────────────────────────┘
              ↓
┌────────────────────────────────────┐
│ Base de Données                     │
│                                     │
│ INSERT INTO payments (              │
│   payment_number,                   │
│   registration_id,                  │
│   scolar_fee_id,                    │
│   month,                            │
│   rate_id,                          │
│   user_id,                          │
│   is_paid                           │
│ ) VALUES (                          │
│   'PAY-673b8e2c3f4a0-7',           │
│   42,                               │
│   105,                              │
│   11,                               │
│   1,                                │
│   7,                                │
│   false                             │
│ )                                   │
└────────────────────────────────────┘
              ↓
┌────────────────────────────────────┐
│ Réponse au Component                │
│ [                                   │
│   'success' => true,                │
│   'message' => 'Paiement enr.ok'    │
│ ]                                   │
└────────────────────────────────────┘
              ↓
┌────────────────────────────────────┐
│ Interface Utilisateur               │
│ ✓ Message de succès affiché         │
│ ✓ Tableau des paiements actualisé   │
│ ✓ Solde mis à jour                  │
└────────────────────────────────────┘
```

---

## 📊 Cas d'Utilisation Réels

### **Cas 1: Paiement Bloqué (Élève a une Dette)**

**Situation:**

-   Année scolaire: 2024-2025
-   Élève inscrit: SEPTEMBRE 2024
-   État des paiements:
    -   SEPTEMBRE: PAYÉ ✓
    -   OCTOBRE: IMPAYÉ ✗
    -   NOVEMBRE: Essaye de payer

**Exécution:**

```php
$service = new StudentDebtTrackerService();
$result = $service->payForMonth(42, 5, 'NOVEMBRE');
```

**Résultat:**

```php
[
  'success' => false,
  'message' => "L'élève a une dette sur le mois d'OCTOBRE. Veuillez régulariser avant de payer NOVEMBRE."
]
```

**Action:** Paiement REFUSÉ. Afficher erreur à l'utilisateur.

---

### **Cas 2: Paiement par Tranche (Pas de Vérification)**

**Situation:**

-   Catégorie: "Frais Inscription" (is_paid_in_installment = true)
-   Élève inscrit: SEPTEMBRE
-   État: Premier paiement (pas de mois antérieur)

**Exécution:**

```php
$service = new StudentDebtTrackerService();
$result = $service->payForMonth(42, 3, 'NOVEMBRE');  // categoryFeeId: 3
```

**Résultat:**

```php
[
  'success' => true,
  'message' => 'Paiement enregistré avec succès.'
]
```

**Action:** Paiement ACCEPTÉ immédiatement (pas de vérification de dette car paiement unique).

---

### **Cas 3: Élève Nouvellement Inscrit**

**Situation:**

-   Élève inscrit: 10 NOVEMBRE
-   Essaye de payer: NOVEMBRE (premier mois)
-   Aucun mois antérieur à vérifier

**Exécution:**

```php
$service = new StudentDebtTrackerService();
$result = $service->canPayForMonth(42, 5, 'NOVEMBRE');
```

**Résultat:**

```php
[
  'can_pay' => true,
  'first_unpaid_month' => null,
  'message' => 'Paiement autorisé.'
]
```

**Raison:** Pas de mois antérieur après l'inscription (NOVEMBRE est le premier mois).

---

### **Cas 4: Inscription Partielle dans l'Année**

**Situation:**

-   Élève inscrit: 15 OCTOBRE 2024
-   Frais commencent: SEPTEMBRE (avant inscription)
-   Essaye de payer: NOVEMBRE

**Exécution:**

```php
$service = new StudentDebtTrackerService();
$result = $service->canPayForMonth(42, 5, 'NOVEMBRE');
```

**Vérification:**

1. SEPTEMBRE (avant NOVEMBRE): Date inscription (15/10) > 01/09 → IGNORER
2. OCTOBRE (avant NOVEMBRE): Date inscription (15/10) < fin/10 → VÉRIFIER

**Résultat:**

```php
[
  'can_pay' => true ou false,  // Dépend si OCTOBRE payé
  'first_unpaid_month' => 'OCTOBRE' ou null,
  'message' => '...'
]
```

---

## ⚠️ Points Importants

### **1. Mois d'Inscription**

-   Le service vérifie `$registration->created_at`
-   Mois antérieurs à l'inscription sont IGNORÉS
-   Exemple: Inscrit 15/10, paiement NOVEMBRE → seul OCTOBRE vérifié

### **2. Type de Paiement**

-   **is_paid_in_installment = true** → Pas de vérification (paiement unique)
-   **is_paid_in_installment = false** → Vérification des mois précédents

### **3. État du Paiement**

-   `is_paid = false` → Paiement enregistré mais pas encore payé
-   `is_paid = true` → Paiement réellement effectué
-   **Le service crée un Payment avec is_paid = false** (pending)

### **4. Année Scolaire**

-   Uniquement la `school_year_id` ACTIVE est vérifiée
-   Utilise `SchoolYear::DEFAULT_SCHOOL_YEAR_ID()`

### **5. Cycle Scolaire**

-   Ordre des mois: SEPTEMBRE → OCTOBRE → ... → AOÛT
-   **NON** l'ordre calendrier (janvier → décembre)
-   Permet année scolaire 2024-2025 de couvrir 9/2024 à 6/2025

---

## 🔗 Intégrations

### **Utilisé par:**

-   `NewPaymentPage` (Livewire) - Interface de paiement
-   `MainPaymentPage` (Livewire) - Rapport des paiements

### **Dépend de:**

-   `DateFormatHelper::getMonthsNumber()` - Conversion mois
-   `SchoolYear::DEFAULT_SCHOOL_YEAR_ID()` - Année active
-   `Rate::DEFAULT_RATE_ID()` - Taux par défaut
-   `Auth::id()` - Utilisateur connecté

---

## 📝 Résumé Rapide

| Aspect                 | Détail                                              |
| ---------------------- | --------------------------------------------------- |
| **Objectif**           | Vérifier si un élève peut payer frais sans dette    |
| **Classe**             | `StudentDebtTrackerService`                         |
| **Méthode Principale** | `payForMonth(registrationId, categoryFeeId, month)` |
| **Vérification**       | `canPayForMonth()`                                  |
| **Retour**             | Array avec success flag + message                   |
| **DB Operation**       | INSERT dans table `payments`                        |
| **Validation**         | Catégorie, inscription, frais, mois antérieurs      |
| **Exception**          | Aucune (retourne array errors)                      |
| **Transaction**        | Non (pas de transaction DB)                         |

---

## ✨ Flux Recommandé d'Utilisation

```php
// Dans votre Livewire Component ou Controller:

use App\Services\StudentDebtTrackerService;

class NewPaymentPage extends Component {
    public function submitPayment() {
        $service = new StudentDebtTrackerService();

        // Appeler la méthode
        $result = $service->payForMonth(
            registrationId: $this->registrationId,
            categoryFeeId: $this->categoryFeeId,
            targetMonth: $this->selectedMonth,
            paymentData: ['is_paid' => $this->paymentStatus]
        );

        // Gérer le résultat
        if ($result['success']) {
            session()->flash('success', $result['message']);
            $this->dispatch('paymentCreated');
        } else {
            session()->flash('error', $result['message']);
        }
    }
}
```

---

**Cette documentation couvre 100% du service StudentDebtTrackerService. Vous avez une compréhension complète de son fonctionnement! 🎉**
