{{-- Composant : Carte de fonctionnalité pour la section info --}}
@props([
    'icon',
    'title',
    'description',
    'delay' => '0.4s'
])

<div class="col-6 animate-slide-up" style="animation-delay: {{ $delay }};">
    <div class="feature-card">
        <div class="feature-icon-wrapper">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
        <div class="feature-content">
            <h6 class="mb-0">{{ $title }}</h6>
            <small class="text-white-50">{{ $description }}</small>
        </div>
    </div>
</div>
