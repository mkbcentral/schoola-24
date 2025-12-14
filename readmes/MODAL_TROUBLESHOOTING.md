# 🔧 Guide de Résolution - Modal Bloqué

## 📍 Symptôme

Le modal s'affiche mais reste complètement figé. Impossible de cliquer sur les boutons, impossible de fermer, même en cliquant sur le backdrop.

## 🔍 Diagnostic en 3 étapes

### Étape 1 : Test Bootstrap PURE

Ouvrez dans votre navigateur : `http://localhost/test-modal-pure.html`

**Si le modal fonctionne ici :**
✅ Bootstrap est OK
❌ Le problème vient de votre CSS/JS personnalisé
→ Passez à l'étape 2

**Si le modal NE fonctionne PAS ici :**
❌ Problème plus profond (cache navigateur, version Bootstrap)
→ Vider le cache (Ctrl+Shift+Del) et réessayer

---

### Étape 2 : Vérifier le Console du navigateur

1. Ouvrez le modal problématique
2. Appuyez sur F12
3. Allez dans l'onglet "Console"

**Recherchez ces messages :**

```javascript
✅ Bootstrap chargé             // Bon signe
❌ Bootstrap NON chargé !       // PROBLÈME

✅ Modal opened: staticBackdrop // Le modal s'ouvre
🔴 Modal closing: staticBackdrop // Le modal se ferme

// Si vous ne voyez AUCUN message de fermeture quand vous cliquez :
→ Les événements sont bloqués
```

**Messages d'erreur à chercher :**

-   `Uncaught TypeError` → Erreur JavaScript
-   `Failed to execute` → Conflit de scripts
-   Tout message contenant "modal" ou "accessibility"

---

### Étape 3 : Test des clics

Tapez dans la console :

```javascript
// Test 1 : Vérifier les styles
const modal = document.querySelector(".modal.show");
console.log("pointer-events:", getComputedStyle(modal).pointerEvents);
console.log("z-index:", getComputedStyle(modal).zIndex);

// Test 2 : Fermer manuellement
forceCloseModal("staticBackdrop");

// Test 3 : Vérifier Bootstrap
const modalElement = document.getElementById("staticBackdrop");
const bsModal = bootstrap.Modal.getInstance(modalElement);
console.log("Instance Bootstrap:", bsModal);
```

**Résultats attendus :**

-   `pointer-events: "auto"` ✅
-   `pointer-events: "none"` ❌ **PROBLÈME CSS**
-   `z-index: "1055"` ✅
-   Instance Bootstrap existe ✅

---

## 🛠️ Solutions selon le problème détecté

### Problème A : `pointer-events: none`

**Cause :** CSS qui bloque les clics

**Solution :**

1. Recompiler les assets :

```bash
npm run build
```

2. Vider le cache du navigateur (Ctrl+Shift+Del)

3. Si le problème persiste, ajouter temporairement dans votre page :

```html
<style>
    .modal,
    .modal-dialog,
    .modal-content,
    .modal button,
    .modal [data-bs-dismiss] {
        pointer-events: auto !important;
    }
</style>
```

---

### Problème B : Événements JavaScript interceptés

**Cause :** `accessibility.js` ou autre script qui intercepte les clics

**Solution :**

1. Vérifier que `accessibility.js` a été mis à jour
2. Recompiler : `npm run build`
3. Vérifier dans la console qu'il n'y a pas d'écouteurs d'événements multiples

**Test temporaire :** Désactiver `accessibility.js`
Commentez dans `app.blade.php` :

```blade
{{-- @vite(['resources/js/accessibility.js']) --}}
```

---

### Problème C : Z-index incorrect

**Cause :** Le backdrop couvre le modal

**Solution :**
Vérifier dans la console :

```javascript
const modal = document.querySelector(".modal.show");
const backdrop = document.querySelector(".modal-backdrop");
console.log("Modal z-index:", getComputedStyle(modal).zIndex);
console.log("Backdrop z-index:", getComputedStyle(backdrop).zIndex);
```

**Le modal DOIT avoir un z-index plus élevé que le backdrop**

-   Backdrop: 1040
-   Modal: 1055

Si ce n'est pas le cas, forcer dans CSS :

```scss
.modal {
    z-index: 1055 !important;
}
.modal-backdrop {
    z-index: 1040 !important;
}
```

---

### Problème D : Instance Bootstrap non créée

**Cause :** Bootstrap n'initialise pas le modal

**Solution :**

```javascript
// Créer manuellement l'instance
const modalElement = document.getElementById("votre-modal-id");
const modal = new bootstrap.Modal(modalElement);
modal.show();
```

---

## ✅ Checklist de vérification finale

Avant de dire que c'est résolu, testez :

-   [ ] Le modal s'ouvre
-   [ ] Le bouton X (btn-close) ferme le modal
-   [ ] Le bouton "Close" ferme le modal
-   [ ] La touche Escape ferme le modal (si `data-bs-keyboard` non défini ou ="true")
-   [ ] Clic sur le backdrop ferme le modal (si `data-bs-backdrop` non défini ou !="static")
-   [ ] Tous les boutons dans le modal sont cliquables
-   [ ] Les champs de formulaire dans le modal sont éditables
-   [ ] Le modal se ferme complètement (backdrop disparaît)
-   [ ] On peut rouvrir le modal après l'avoir fermé

---

## 🚨 Si RIEN ne fonctionne

**Option nucléaire :**

1. Désactiver TOUS les CSS/JS personnalisés
2. Utiliser uniquement Bootstrap CDN
3. Tester le modal

Si ça marche → réactiver un par un les fichiers pour trouver le coupable

**Commandes de nettoyage complet :**

```bash
# Vider tous les caches
npm run build
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Redémarrer le serveur
php artisan serve
```

---

## 📞 Informations pour le support

Si vous demandez de l'aide, fournissez :

1. **Message de la console** (F12 → Console)
2. **Valeurs des tests :**
    - `pointer-events` du modal
    - `z-index` du modal et backdrop
    - Instance Bootstrap existe ?
3. **Le test Bootstrap PURE fonctionne-t-il ?**
4. **Version de Bootstrap** (visible dans console : `bootstrap.Modal.VERSION`)
5. **Navigateur et version**

---

**Dernière mise à jour :** 8 décembre 2025
