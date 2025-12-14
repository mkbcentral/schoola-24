{{-- Theme Toggle Button Component --}}
<div class="theme-toggle-wrapper" x-data="{ theme: localStorage.getItem('schoola-theme') || 'light' }">
    <button type="button" @click="theme = window.toggleTheme(); $nextTick(() => { theme = window.getTheme(); })"
        class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2"
        :title="theme === 'dark' ? 'Passer en mode clair' : 'Passer en mode sombre'"
        :aria-label="theme === 'dark' ? 'Passer en mode clair' : 'Passer en mode sombre'"
        style="border-radius: 20px; padding: 0.5rem 1rem; transition: all 0.3s ease;">

        {{-- Icône soleil (mode clair) --}}
        <i class="bi bi-sun-fill" x-show="theme === 'light'" style="font-size: 1.1rem; color: #fbbf24;">
        </i>

        {{-- Icône lune (mode sombre) --}}
        <i class="bi bi-moon-fill" x-show="theme === 'dark'" style="font-size: 1.1rem; color: #60a5fa;">
        </i>

        <span class="d-none d-md-inline small fw-semibold" x-text="theme === 'dark' ? 'Sombre' : 'Clair'"></span>
    </button>
</div>

<style>
    .theme-toggle-wrapper button {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-width: 1.5px;
    }

    .theme-toggle-wrapper button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .theme-toggle-wrapper button:active {
        transform: scale(0.95);
    }

    /* Animation des icônes */
    .theme-toggle-wrapper i {
        transition: transform 0.3s ease;
    }

    .theme-toggle-wrapper button:hover i {
        transform: rotate(20deg);
    }

    /* Styles en mode sombre */
    [data-bs-theme="dark"] .theme-toggle-wrapper button,
    .dark-mode .theme-toggle-wrapper button {
        border-color: rgba(255, 255, 255, 0.2);
        background-color: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.9);
    }

    [data-bs-theme="dark"] .theme-toggle-wrapper button:hover,
    .dark-mode .theme-toggle-wrapper button:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>
