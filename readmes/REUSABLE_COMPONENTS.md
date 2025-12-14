# Composants Réutilisables - Schoola Web

Ce document liste tous les composants Blade réutilisables extraits du module de paiement.

## 1. Stats Card (`<x-stats-card>`)

**Emplacement:** `resources/views/components/stats-card.blade.php`

**Description:** Carte de statistique avec icône circulaire et valeur.

**Props:**

-   `icon` (requis) : Nom de l'icône Bootstrap Icons (sans le préfixe `bi-`)
-   `label` (requis) : Label de la statistique
-   `value` (requis) : Valeur à afficher
-   `iconColor` (optionnel) : Classe de couleur pour l'icône (défaut: `text-primary`)
-   `iconBg` (optionnel) : Classe de background pour le cercle (défaut: `bg-light`)

**Exemples d'utilisation:**

```blade
<!-- Statistique simple -->
<x-stats-card
    icon="collection"
    label="Total"
    :value="number_format($totalCount)"
/>

<!-- Avec couleurs personnalisées -->
<x-stats-card
    icon="check-circle"
    label="Payés"
    :value="number_format($paidCount)"
    iconColor="text-success"
    iconBg="bg-success-subtle"
/>

<!-- Avec background personnalisé inline -->
<div class="col-md-3">
    <x-stats-card
        icon="x-circle"
        label="Non payés"
        :value="number_format($unpaidCount)"
        iconColor="text-danger"
        iconBg="opacity-0"
        style="--bs-bg-opacity: .1; background-color: rgba(234, 84, 85, 0.1) !important;"
    />
</div>
```

---

## 2. Search Bar (`<x-search-bar>`)

**Emplacement:** `resources/views/components/search-bar.blade.php`

**Description:** Barre de recherche minimaliste avec icône et bouton de réinitialisation.

**Props:**

-   `placeholder` (optionnel) : Texte du placeholder (défaut: "Rechercher...")
-   `model` (optionnel) : Nom du modèle Livewire (défaut: "search")
-   `resultCount` (optionnel) : Nombre de résultats à afficher
-   `searchTerm` (optionnel) : Terme de recherche actuel

**Exemple d'utilisation:**

```blade
<x-search-bar
    placeholder="Rechercher un élève..."
    model="search"
    :resultCount="$totalCount"
    :searchTerm="$search"
/>
```

---

## 3. Loading Overlay (`<x-loading-overlay>`)

**Emplacement:** `resources/views/components/loading-overlay.blade.php`

**Description:** Overlay de chargement avec spinner centré et fond flouté.

**Props:**

-   `title` (optionnel) : Titre du message (défaut: "Chargement en cours...")
-   `subtitle` (optionnel) : Sous-titre (défaut: "Veuillez patienter")

**Exemple d'utilisation:**

```blade
<!-- Avec texte par défaut -->
<x-loading-overlay />

<!-- Avec texte personnalisé -->
<x-loading-overlay
    title="Filtrage en cours..."
    subtitle="Veuillez patienter"
/>
```

---

## 4. Academic Filters (`<x-academic-filters>`)

**Emplacement:** `resources/views/components/academic-filters.blade.php`

**Description:** Filtres en cascade Section > Option > Classe.

**Props:**

-   `sections` (requis) : Collection des sections
-   `options` (requis) : Collection des options (filtrées par section)
-   `classRooms` (requis) : Collection des classes (filtrées par option)
-   `sectionId` (requis) : ID de la section sélectionnée
-   `optionId` (requis) : ID de l'option sélectionnée

**Exemple d'utilisation:**

```blade
<x-academic-filters
    :sections="$sections"
    :options="$options"
    :classRooms="$classRooms"
    :sectionId="$sectionId"
    :optionId="$optionId"
/>
```

**Note:** Nécessite les méthodes Livewire `updatedSectionId()`, `updatedOptionId()`, `updatedClassRoomId()`.

---

## 5. Month Select (`<x-month-select>`)

**Emplacement:** `resources/views/components/month-select.blade.php`

**Description:** Select avec tous les mois de l'année en français.

**Props:**

-   `model` (optionnel) : Nom du modèle Livewire (défaut: "month")

**Exemple d'utilisation:**

```blade
<!-- Avec modèle par défaut -->
<x-month-select />

<!-- Avec modèle personnalisé -->
<x-month-select model="selectedMonth" />
```

---

## 6. Date Range Select (`<x-date-range-select>`)

**Emplacement:** `resources/views/components/date-range-select.blade.php`

**Description:** Select avec des plages de dates prédéfinies (cette semaine, ce mois, derniers 3/6/9 mois, etc.).

**Props:**

-   `model` (optionnel) : Nom du modèle Livewire (défaut: "dateRange")
-   `dateRange` (optionnel) : Valeur actuelle du dateRange pour afficher le message d'info

**Exemple d'utilisation:**

```blade
<x-date-range-select
    model="dateRange"
    :dateRange="$dateRange"
/>
```

**Options disponibles:**

-   `this_week` : Cette semaine
-   `last_2_weeks` : Il y a 2 semaines
-   `last_3_weeks` : Il y a 3 semaines
-   `this_month` : Ce mois
-   `last_3_months` : Il y a 3 mois
-   `last_6_months` : Il y a 6 mois
-   `last_9_months` : Il y a 9 mois

---

## 7. Empty State (`<x-empty-state>`)

**Emplacement:** `resources/views/components/empty-state.blade.php`

**Description:** État vide pour les tableaux (message avec icône).

**Props:**

-   `icon` (optionnel) : Nom de l'icône Bootstrap Icons (défaut: "inbox")
-   `message` (optionnel) : Message à afficher (défaut: "Aucune donnée trouvée")
-   `colspan` (optionnel) : Nombre de colonnes à couvrir (défaut: 10)

**Exemple d'utilisation:**

```blade
<tbody>
    @forelse($items as $item)
        <tr>...</tr>
    @empty
        <x-empty-state
            icon="inbox"
            message="Aucun paiement trouvé avec ces critères"
            colspan="9"
        />
    @endforelse
</tbody>
```

---

## 8. Pagination Info (`<x-pagination-info>`)

**Emplacement:** `resources/views/components/pagination-info.blade.php`

**Description:** Affiche les informations de pagination avec les liens de navigation.

**Props:**

-   `paginator` (requis) : Objet de pagination Laravel

**Exemple d'utilisation:**

```blade
<x-pagination-info :paginator="$payments" />
```

---

## Exemple Complet d'Intégration

Voici comment refactoriser la page de paiements avec ces composants :

```blade
<div class="container-fluid py-4">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 text-white fw-light">
                            <i class="bi bi-cash-stack me-2"></i>Liste des Paiements
                        </h5>
                        <button wire:click="resetFilters" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                        </button>
                    </div>

                    <!-- Stats avec composants -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <x-stats-card icon="collection" label="Total" :value="number_format($totalCount)" />
                        </div>
                        <div class="col-md-3">
                            <x-stats-card
                                icon="check-circle"
                                label="Payés"
                                :value="number_format($statistics['paid_count'])"
                                iconColor="text-success"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-stats-card
                                icon="x-circle"
                                label="Non payés"
                                :value="number_format($statistics['unpaid_count'])"
                                iconColor="text-danger"
                            />
                        </div>
                        <div class="col-md-3">
                            <x-stats-card
                                icon="graph-up-arrow"
                                label="Taux"
                                :value="number_format($statistics['payment_rate'], 1) . '%'"
                                iconColor="text-info"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Barre de recherche -->
                    <x-search-bar
                        placeholder="Rechercher un élève..."
                        :resultCount="$totalCount"
                        :searchTerm="$search"
                    />

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>...</thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>...</tr>
                                @empty
                                    <x-empty-state
                                        message="Aucun paiement trouvé avec ces critères"
                                        colspan="9"
                                    />
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <x-pagination-info :paginator="$payments" />
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay de chargement -->
    <x-loading-overlay title="Filtrage en cours..." />
</div>
```

---

## Avantages de ces Composants

1. **Réutilisabilité** : Utilisables dans tous les modules (inscriptions, frais, rapports, etc.)
2. **Consistance** : Design uniforme dans toute l'application
3. **Maintenabilité** : Modifications centralisées
4. **Productivité** : Développement plus rapide
5. **Lisibilité** : Code plus propre et plus facile à comprendre

---

## Prochaines Étapes

Pour continuer l'amélioration :

1. Créer un composant `<x-gradient-card-header>` pour les en-têtes avec gradient
2. Créer un composant `<x-offcanvas-filter>` pour les filtres dans un offcanvas
3. Créer un composant `<x-action-buttons>` pour les boutons d'action (voir, modifier, supprimer)
4. Créer un composant `<x-payment-status-badge>` pour les badges de statut
