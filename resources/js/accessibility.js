/**
 * Utilities JavaScript pour l'Accessibilité - Schoola
 * Gestion du focus, navigation clavier, ARIA live regions
 */

class AccessibilityManager {
    constructor() {
        this.focusTrapStack = [];
        this.init();
    }

    init() {
        this.setupKeyboardNavigation();
        this.setupFocusManagement();
        this.setupAriaLiveRegions();
        this.setupSkipLinks();
        this.monitorKeyboardUsage();
    }

    /**
     * Configuration de la navigation au clavier
     */
    setupKeyboardNavigation() {
        // DÉSACTIVÉ COMPLÈTEMENT pour ne pas interférer avec Bootstrap
        // Bootstrap gère déjà tous les événements clavier des modals
        
        // Navigation par Tab (conservée pour l'indicateur visuel)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        // Retirer la classe lors d'un clic
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });

        // Arrow keys pour navigation dans les listes (non modals)
        // this.setupArrowKeyNavigation(); // DÉSACTIVÉ
    }

    /**
     * Navigation avec les touches fléchées
     */
    setupArrowKeyNavigation() {
        document.querySelectorAll('[role="listbox"], [role="menu"], [role="tablist"]').forEach(container => {
            container.addEventListener('keydown', (e) => {
                const items = Array.from(container.querySelectorAll('[role="option"], [role="menuitem"], [role="tab"]'));
                const currentIndex = items.indexOf(document.activeElement);

                if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % items.length;
                    items[nextIndex].focus();
                } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    const prevIndex = (currentIndex - 1 + items.length) % items.length;
                    items[prevIndex].focus();
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    items[0].focus();
                } else if (e.key === 'End') {
                    e.preventDefault();
                    items[items.length - 1].focus();
                }
            });
        });
    }

    /**
     * Gestion du focus (Focus Trap pour modals)
     */
    setupFocusManagement() {
        // DÉSACTIVÉ COMPLÈTEMENT - Bootstrap gère déjà le focus des modals
        // Ne rien faire ici pour ne pas interférer
    }

    /**
     * Piège le focus dans un élément (modal, dialog)
     */
    trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );

        if (focusableElements.length === 0) return;

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        // Sauvegarder l'élément actif avant le trap
        const previousFocus = document.activeElement;
        this.focusTrapStack.push({ element, previousFocus });

        // Focus sur le premier élément
        setTimeout(() => firstElement.focus(), 100);

        // Handler pour le trap
        const trapHandler = (e) => {
            if (e.key !== 'Tab') return;

            if (e.shiftKey) {
                // Tab + Shift
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                // Tab seul
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        };

        element.addEventListener('keydown', trapHandler);
        element._focusTrapHandler = trapHandler;
    }

    /**
     * Libère le focus trap
     */
    releaseFocusTrap(element) {
        if (element._focusTrapHandler) {
            element.removeEventListener('keydown', element._focusTrapHandler);
            delete element._focusTrapHandler;
        }

        const trap = this.focusTrapStack.pop();
        if (trap && trap.previousFocus) {
            trap.previousFocus.focus();
        }
    }

    /**
     * Gestion de la touche Escape
     */
    handleEscape() {
        // DÉSACTIVÉ COMPLÈTEMENT - Bootstrap gère tout
        // Ne rien faire pour ne pas interférer avec les modals
    }

    /**
     * Configuration des ARIA Live Regions
     */
    setupAriaLiveRegions() {
        // Créer une région live pour les annonces
        if (!document.getElementById('aria-live-region')) {
            const liveRegion = document.createElement('div');
            liveRegion.id = 'aria-live-region';
            liveRegion.setAttribute('role', 'status');
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'visually-hidden';
            document.body.appendChild(liveRegion);
        }

        // Créer une région pour les alertes
        if (!document.getElementById('aria-alert-region')) {
            const alertRegion = document.createElement('div');
            alertRegion.id = 'aria-alert-region';
            alertRegion.setAttribute('role', 'alert');
            alertRegion.setAttribute('aria-live', 'assertive');
            alertRegion.setAttribute('aria-atomic', 'true');
            alertRegion.className = 'visually-hidden';
            document.body.appendChild(alertRegion);
        }
    }

    /**
     * Annonce un message aux lecteurs d'écran
     */
    announce(message, isAlert = false) {
        const regionId = isAlert ? 'aria-alert-region' : 'aria-live-region';
        const region = document.getElementById(regionId);

        if (region) {
            region.textContent = '';
            setTimeout(() => {
                region.textContent = message;
            }, 100);
        }
    }

    /**
     * Skip links (aller au contenu principal)
     */
    setupSkipLinks() {
        const mainContent = document.querySelector('main, [role="main"], #content');
        if (mainContent && !mainContent.hasAttribute('tabindex')) {
            mainContent.setAttribute('tabindex', '-1');
        }

        document.querySelectorAll('.skip-to-main').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                if (mainContent) {
                    mainContent.focus();
                    mainContent.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    /**
     * Détecter l'utilisation du clavier
     */
    monitorKeyboardUsage() {
        let usingKeyboard = false;

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                usingKeyboard = true;
                document.body.classList.add('using-keyboard');
            }
        });

        document.addEventListener('mousedown', () => {
            usingKeyboard = false;
            document.body.classList.remove('using-keyboard');
        });
    }

    /**
     * Vérifier le contraste des couleurs
     */
    static checkColorContrast(foreground, background) {
        // Convertir hex en RGB
        const hexToRgb = (hex) => {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        };

        // Calculer la luminance relative
        const getLuminance = (rgb) => {
            const [r, g, b] = [rgb.r, rgb.g, rgb.b].map(val => {
                val = val / 255;
                return val <= 0.03928 ? val / 12.92 : Math.pow((val + 0.055) / 1.055, 2.4);
            });
            return 0.2126 * r + 0.7152 * g + 0.0722 * b;
        };

        const fg = hexToRgb(foreground);
        const bg = hexToRgb(background);

        if (!fg || !bg) return null;

        const l1 = getLuminance(fg);
        const l2 = getLuminance(bg);

        const ratio = (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);

        return {
            ratio: ratio.toFixed(2),
            passAA: ratio >= 4.5,
            passAAA: ratio >= 7,
            level: ratio >= 7 ? 'AAA' : ratio >= 4.5 ? 'AA' : 'Fail'
        };
    }

    /**
     * Valider l'accessibilité d'un formulaire
     */
    static validateFormAccessibility(form) {
        const issues = [];

        // Vérifier les labels
        form.querySelectorAll('input, select, textarea').forEach(input => {
            const id = input.id;
            const label = form.querySelector(`label[for="${id}"]`);

            if (!label && !input.hasAttribute('aria-label') && !input.hasAttribute('aria-labelledby')) {
                issues.push(`Input ${id || 'sans ID'} n'a pas de label`);
            }
        });

        // Vérifier les required
        form.querySelectorAll('[required]').forEach(input => {
            if (!input.hasAttribute('aria-required')) {
                input.setAttribute('aria-required', 'true');
            }
        });

        // Vérifier les messages d'erreur
        form.querySelectorAll('.is-invalid, [aria-invalid="true"]').forEach(input => {
            const errorId = input.getAttribute('aria-describedby');
            if (!errorId || !document.getElementById(errorId)) {
                issues.push(`Input ${input.id || 'sans ID'} n'a pas de message d'erreur associé`);
            }
        });

        return issues;
    }
}

// Initialiser le manager d'accessibilité
const accessibilityManager = new AccessibilityManager();

// Exposer globalement
window.AccessibilityManager = AccessibilityManager;
window.a11y = accessibilityManager;

// Utilitaires globaux
window.announce = (message, isAlert = false) => {
    accessibilityManager.announce(message, isAlert);
};

// Helper pour les composants Livewire
document.addEventListener('livewire:navigated', () => {
    accessibilityManager.setupArrowKeyNavigation();
});

// Annoncer les erreurs de validation
document.addEventListener('invalid', (e) => {
    const input = e.target;
    const label = document.querySelector(`label[for="${input.id}"]`);
    const fieldName = label ? label.textContent : input.name;

    accessibilityManager.announce(
        `Erreur de validation: ${fieldName} ${input.validationMessage}`,
        true
    );
}, true);

// Annoncer les succès
document.addEventListener('DOMContentLoaded', () => {
    // Écouter les événements Livewire
    document.addEventListener('success', (e) => {
        if (e.detail && e.detail.message) {
            accessibilityManager.announce(e.detail.message, false);
        }
    });

    document.addEventListener('error', (e) => {
        if (e.detail && e.detail.message) {
            accessibilityManager.announce(e.detail.message, true);
        }
    });
});

console.log('✅ Accessibility Manager chargé');
