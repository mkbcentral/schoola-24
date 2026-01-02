{{-- Composant : Alertes pour la page de connexion --}}
@props([
    'type' => 'danger',
    'icon' => 'exclamation-triangle-fill',
    'title' => null,
    'message',
    'dismissible' => true
])

<div class="alert alert-{{ $type }} @if($dismissible) alert-dismissible @endif fade show d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-{{ $icon }} me-2 @if(!$title) fs-4 @endif"></i>
    <div @if($dismissible) class="flex-grow-1" @endif>
        @if($title)
            <strong>{{ $title }}</strong><br>
        @endif
        <small>{{ $message }}</small>
    </div>
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
