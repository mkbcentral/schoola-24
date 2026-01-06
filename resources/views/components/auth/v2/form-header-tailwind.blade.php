{{-- Composant Form Header - Version Tailwind CSS --}}
<div class="text-center mb-8 animate-fade-in">
    <div class="mb-6 flex justify-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-16 w-auto">
    </div>
    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Bienvenue !</h2>
    <p class="text-gray-500 dark:text-gray-400 mb-0">Connectez-vous à votre compte</p>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>
