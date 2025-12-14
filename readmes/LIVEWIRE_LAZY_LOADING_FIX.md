# 🔧 Fix: JavaScript avec Livewire Lazy Loading

## 🐛 Problème Identifié

Lorsque vous utilisez `Route::get()->lazy()`, les composants Livewire sont chargés de manière asynchrone APRÈS le chargement initial de la page. Les scripts dans `@push('js')` avec `<script type="module">` sont exécutés IMMÉDIATEMENT, mais les éléments DOM n'existent pas encore.

### Symptômes

-   ✅ Les scripts fonctionnent SANS `->lazy()`
-   ❌ Les scripts ne fonctionnent PAS AVEC `->lazy()`
-   Les événements Livewire ne sont pas capturés
-   Les sélecteurs DOM retournent `null`

---

## ✅ Solution 1: Utiliser `livewire:init` (Recommandé)

### ❌ AVANT (Ne fonctionne pas avec lazy)

```blade
@push('js')
    <script type="module">
        window.addEventListener('mon-event', event => {
            // Ce code s'exécute AVANT le chargement du composant lazy
            console.log('Event:', event.detail);
        });
    </script>
@endpush
```

### ✅ APRÈS (Fonctionne avec lazy)

```blade
<script>
    document.addEventListener('livewire:init', () => {
        // Ce code s'exécute APRÈS l'initialisation de Livewire
        window.addEventListener('mon-event', event => {
            console.log('Event:', event.detail);
        });
    });
</script>
```

**Avantages:**

-   ✅ Compatible avec `lazy()`
-   ✅ S'exécute au bon moment
-   ✅ Pas besoin de `type="module"`
-   ✅ Fonctionne avec tous les événements Livewire

---

## ✅ Solution 2: Utiliser `wire:init` dans le composant

### Dans le fichier Blade

```blade
<div wire:init="loadComponent">
    <!-- Contenu du composant -->
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('component-loaded', () => {
            // Scripts à exécuter après le chargement
            console.log('Composant chargé!');
        });
    });
</script>
```

### Dans le composant PHP

```php
public function loadComponent()
{
    // Charger les données
    $this->dispatch('component-loaded');
}
```

---

## ✅ Solution 3: Scripts globaux dans le layout

Pour les scripts qui doivent être disponibles globalement (SweetAlert, événements communs):

### Dans `app.blade.php`

```blade
@stack('js')

<script>
    // Scripts globaux disponibles pour tous les composants
    document.addEventListener('livewire:init', () => {
        // Confirmation de suppression générique
        Livewire.on('confirm-delete', (data) => {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: data.message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(data.callback);
                }
            });
        });
    });
</script>
```

---

## 🔍 Diagnostic: Vérifier si un composant est lazy

### Dans routes/web.php

```php
// ❌ Lazy - nécessite livewire:init
Route::get('/students', ListStudentPage::class)->lazy();

// ✅ Normal - scripts fonctionnent directement
Route::get('/students', ListStudentPage::class);
```

---

## 📋 Checklist de Migration

Pour chaque fichier `.blade.php` avec `@push('js')`:

-   [ ] 1. Identifier les scripts affectés
-   [ ] 2. Remplacer `@push('js')` par `<script>` direct
-   [ ] 3. Envelopper dans `livewire:init`
-   [ ] 4. Supprimer `type="module"` si présent
-   [ ] 5. Tester avec `lazy()` activé
-   [ ] 6. Vérifier les événements Livewire

---

## 🔧 Pattern de Migration Standard

### Template à utiliser

```blade
<div>
    <!-- Contenu du composant -->
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // ===== EVENT LISTENERS =====
        window.addEventListener('nom-event', event => {
            // Logique ici
        });

        // ===== LIVEWIRE EVENTS =====
        Livewire.on('mon-livewire-event', (data) => {
            // Logique ici
        });

        // ===== DOM MANIPULATION =====
        // Utiliser setTimeout si besoin d'attendre le rendu
        setTimeout(() => {
            const element = document.getElementById('mon-element');
            if (element) {
                // Manipulation du DOM
            }
        }, 100);
    });
</script>

<!-- Composants enfants Livewire -->
<livewire:mon-composant-enfant />
```

---

## 🎯 Exemples Pratiques

### Exemple 1: SweetAlert avec Lazy Loading

```blade
<script>
    document.addEventListener('livewire:init', () => {
        window.addEventListener('show-confirmation', event => {
            Swal.fire({
                title: event.detail[0].title,
                text: event.detail[0].message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(event.detail[0].callback);
                }
            });
        });

        window.addEventListener('show-success', event => {
            Swal.fire({
                title: 'Succès!',
                text: event.detail[0].message,
                icon: 'success',
                timer: 2000
            });
        });
    });
</script>
```

### Exemple 2: Chart.js avec Lazy Loading

```blade
<div>
    <canvas id="myChart" wire:ignore></canvas>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // Attendre que le canvas soit dans le DOM
        setTimeout(() => {
            const ctx = document.getElementById('myChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: @json($chartData),
                    options: {
                        responsive: true
                    }
                });
            }
        }, 100);
    });
</script>
```

### Exemple 3: Bootstrap Modal avec Lazy Loading

```blade
<script>
    document.addEventListener('livewire:init', () => {
        window.addEventListener('open-modal', event => {
            const modalId = event.detail[0].modalId;
            const modal = document.getElementById(modalId);

            if (modal) {
                const bsModal = bootstrap.Modal.getOrCreateInstance(modal);
                bsModal.show();
            }
        });

        window.addEventListener('close-modal', event => {
            const modalId = event.detail[0].modalId;
            const modal = document.getElementById(modalId);

            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        });
    });
</script>
```

---

## ⚠️ Pièges à Éviter

### ❌ NE PAS FAIRE

```blade
<!-- 1. Type module avec lazy loading -->
@push('js')
    <script type="module">
        // Ne fonctionne pas avec lazy()
    </script>
@endpush

<!-- 2. Sélecteurs DOM directs -->
<script>
    const element = document.getElementById('my-id'); // null avec lazy!
    element.addEventListener('click', ...);
</script>

<!-- 3. DOMContentLoaded avec lazy -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // S'exécute trop tôt avec lazy()
    });
</script>
```

### ✅ FAIRE

```blade
<!-- 1. Sans type module -->
<script>
    document.addEventListener('livewire:init', () => {
        // Fonctionne parfaitement
    });
</script>

<!-- 2. Sélecteurs après initialisation -->
<script>
    document.addEventListener('livewire:init', () => {
        setTimeout(() => {
            const element = document.getElementById('my-id');
            if (element) {
                element.addEventListener('click', ...);
            }
        }, 100);
    });
</script>

<!-- 3. Événements Livewire -->
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('event-name', (data) => {
            // Logique ici
        });
    });
</script>
```

---

## 🧪 Testing

### Test 1: Vérifier l'événement

```javascript
document.addEventListener("livewire:init", () => {
    console.log("✅ Livewire initialisé");

    window.addEventListener("test-event", (event) => {
        console.log("✅ Événement capturé:", event.detail);
    });
});
```

### Test 2: Vérifier le DOM

```javascript
document.addEventListener("livewire:init", () => {
    setTimeout(() => {
        const element = document.getElementById("test-element");
        console.log("Element trouvé:", element ? "✅" : "❌");
    }, 100);
});
```

---

## 📊 Fichiers à Corriger dans le Projet

Rechercher tous les fichiers avec ce pattern:

```bash
grep -r "@push('js')" resources/views/livewire/
grep -r "type=\"module\"" resources/views/livewire/
```

Fichiers prioritaires identifiés:

-   ✅ `list-student-page.blade.php` (CORRIGÉ)
-   `main-registration-page.blade.php`
-   `setting-theme-page.blade.php`
-   `stock-dashboard.blade.php`
-   `main-payment-chart-page.blade.php`
-   Tous les fichiers avec `@push('js')`

---

## 🎓 Résumé

**Règle d'or:** Avec `->lazy()`, TOUJOURS utiliser:

```javascript
document.addEventListener("livewire:init", () => {
    // Votre code ici
});
```

**Pourquoi ça fonctionne:**

1. `lazy()` charge le composant via AJAX après le chargement de la page
2. `livewire:init` est déclenché APRÈS que Livewire soit prêt
3. Les événements et le DOM sont disponibles au bon moment

**Alternative:**
Si vous ne voulez pas utiliser `lazy()`, retirez-le simplement de vos routes.

---

**Date:** 17 novembre 2025  
**Version Livewire:** 3.x  
**Auteur:** GitHub Copilot
