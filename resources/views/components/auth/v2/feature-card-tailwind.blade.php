{{-- Composant Feature Card - Version Tailwind CSS --}}
@props([
    'icon',
    'title',
    'description',
    'delay' => '0.4s'
])

<div class="animate-slide-up" style="animation-delay: {{ $delay }};">
    <div class="flex items-start gap-3 p-4 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/15 transition-all duration-300 hover:-translate-y-1">
        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center">
            <i class="bi bi-{{ $icon }} text-white text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h6 class="text-white font-semibold mb-1 text-sm">{{ $title }}</h6>
            <small class="text-white/70 text-xs">{{ $description }}</small>
        </div>
    </div>
</div>

<style>
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out forwards;
    opacity: 0;
}
</style>
