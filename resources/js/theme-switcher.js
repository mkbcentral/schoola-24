/**
 * Theme Switcher - Gestion des modes sombre et clair
 * Permet de basculer entre les thèmes et de sauvegarder la préférence
 */

class ThemeSwitcher {
    constructor() {
        this.themeKey = 'schoola-theme';
        this.currentTheme = this.getStoredTheme() || this.getPreferredTheme();
        this.init();
    }

    /**
     * Initialise le gestionnaire de thème
     */
    init() {
        // Appliquer le thème stocké
        this.applyTheme(this.currentTheme);

        // Écouter les changements de préférence système
        this.watchSystemTheme();

        // Exposer globalement
        window.themeSwitcher = this;
    }

    /**
     * Obtenir le thème préféré du système
     */
    getPreferredTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    /**
     * Obtenir le thème stocké
     */
    getStoredTheme() {
        return localStorage.getItem(this.themeKey);
    }

    /**
     * Stocker le thème
     */
    setStoredTheme(theme) {
        localStorage.setItem(this.themeKey, theme);
    }

    /**
     * Appliquer le thème
     */
    applyTheme(theme) {
        // Mettre à jour l'attribut data-bs-theme sur l'élément HTML
        document.documentElement.setAttribute('data-bs-theme', theme);
        
        // Ajouter/retirer la classe dark-mode
        if (theme === 'dark') {
            document.documentElement.classList.add('dark-mode');
            document.body.classList.add('dark-mode');
        } else {
            document.documentElement.classList.remove('dark-mode');
            document.body.classList.remove('dark-mode');
        }

        this.currentTheme = theme;
        this.setStoredTheme(theme);

        // Émettre un événement personnalisé
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    /**
     * Basculer entre les thèmes
     */
    toggle() {
        const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.applyTheme(newTheme);
        return newTheme;
    }

    /**
     * Définir un thème spécifique
     */
    setTheme(theme) {
        if (theme !== 'dark' && theme !== 'light') {
            console.warn(`Thème invalide: ${theme}. Utiliser 'dark' ou 'light'.`);
            return;
        }
        this.applyTheme(theme);
    }

    /**
     * Obtenir le thème actuel
     */
    getTheme() {
        return this.currentTheme;
    }

    /**
     * Surveiller les changements de préférence système
     */
    watchSystemTheme() {
        if (!window.matchMedia) return;

        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        // Utiliser addEventListener si disponible
        if (darkModeQuery.addEventListener) {
            darkModeQuery.addEventListener('change', (e) => {
                // Ne changer que si aucun thème n'est stocké
                if (!this.getStoredTheme()) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    this.applyTheme(newTheme);
                }
            });
        } else if (darkModeQuery.addListener) {
            // Fallback pour les anciens navigateurs
            darkModeQuery.addListener((e) => {
                if (!this.getStoredTheme()) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    this.applyTheme(newTheme);
                }
            });
        }
    }

    /**
     * Réinitialiser au thème système
     */
    resetToSystem() {
        localStorage.removeItem(this.themeKey);
        const systemTheme = this.getPreferredTheme();
        this.applyTheme(systemTheme);
    }
}

// Créer un bouton de basculement de thème
function createThemeToggleButton() {
    const button = document.createElement('button');
    button.id = 'theme-toggle-btn';
    button.className = 'btn btn-sm btn-outline-secondary position-fixed';
    button.style.cssText = `
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        padding: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    `;
    button.setAttribute('title', 'Changer de thème');
    button.setAttribute('aria-label', 'Basculer entre les modes sombre et clair');

    const updateButtonIcon = (theme) => {
        if (theme === 'dark') {
            button.innerHTML = '<i class="bi bi-sun-fill" style="font-size: 1.25rem;"></i>';
        } else {
            button.innerHTML = '<i class="bi bi-moon-fill" style="font-size: 1.25rem;"></i>';
        }
    };

    // Icône initiale
    updateButtonIcon(window.themeSwitcher?.getTheme() || 'light');

    // Gestion du clic
    button.addEventListener('click', () => {
        const newTheme = window.themeSwitcher.toggle();
        updateButtonIcon(newTheme);
        
        // Animation de feedback
        button.style.transform = 'scale(0.9)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
    });

    // Écouter les changements de thème
    window.addEventListener('themeChanged', (e) => {
        updateButtonIcon(e.detail.theme);
    });

    document.body.appendChild(button);
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', () => {
    // Initialiser le gestionnaire de thème
    new ThemeSwitcher();

    // Créer le bouton de basculement (optionnel)
    // Décommenter pour afficher un bouton flottant
    // createThemeToggleButton();
});

// Exposer les fonctions utilitaires
window.createThemeToggleButton = createThemeToggleButton;

// API simplifiée pour Livewire
window.setTheme = (theme) => window.themeSwitcher?.setTheme(theme);
window.toggleTheme = () => window.themeSwitcher?.toggle();
window.getTheme = () => window.themeSwitcher?.getTheme();
