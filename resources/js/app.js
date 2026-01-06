import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Initialize Alpine
window.Alpine = Alpine;
Alpine.start();

// Make Chart.js available globally
window.Chart = Chart;

// Log pour vérifier que le JS est chargé
console.log('Schoola App loaded');

// Rafraîchir le token CSRF pour éviter les erreurs 419 avec wire:navigate
document.addEventListener('livewire:navigated', () => {
    // Récupérer le nouveau token CSRF après la navigation
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        // Mettre à jour tous les champs CSRF cachés dans les formulaires
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = token;
        });

        // Mettre à jour axios si disponible
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }
});
