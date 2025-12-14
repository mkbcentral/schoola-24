@props(['title' => 'Chargement en cours...', 'subtitle' => 'Veuillez patienter'])

<!-- Overlay sombre pendant le chargement -->
<div wire:loading class="position-fixed top-0 start-0 w-100 h-100"
    style="background: rgba(0, 0, 0, 0.3); z-index: var(--z-modal-backdrop); backdrop-filter: blur(2px);"></div>

<!-- Indicateur de chargement centré -->
<div wire:loading class="position-fixed top-50 start-50 translate-middle" style="z-index: var(--z-modal);">
    <div class="card shadow-lg border-0" style="min-width: 200px;">
        <div class="card-body text-center py-4">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <div class="fw-bold text-primary">{{ $title }}</div>
            <small class="text-muted">{{ $subtitle }}</small>
        </div>
    </div>
</div>
