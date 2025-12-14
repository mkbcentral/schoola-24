@props(['type' => 'app_status', 'status'])

@if($type === 'app_status')
    @if($status === App\Enums\SchoolAppEnum::IS_FREE)
        <span class="badge bg-info bg-opacity-10 text-info border border-info">
            <i class="bi bi-gift me-1"></i>Gratuit
        </span>
    @elseif($status === App\Enums\SchoolAppEnum::SUBSCRIBED)
        <span class="badge bg-success bg-opacity-10 text-success border border-success">
            <i class="bi bi-star me-1"></i>Abonné
        </span>
    @endif
@elseif($type === 'school_status')
    @if($status === App\Enums\SchoolEnum::PENDING)
        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
            <i class="bi bi-clock me-1"></i>En attente
        </span>
    @elseif($status === App\Enums\SchoolEnum::APPROVED)
        <span class="badge bg-success bg-opacity-10 text-success border border-success">
            <i class="bi bi-check-circle me-1"></i>Approuvé
        </span>
    @elseif($status === App\Enums\SchoolEnum::REJECTED)
        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
            <i class="bi bi-x-circle me-1"></i>Rejeté
        </span>
    @elseif($status === App\Enums\SchoolEnum::SUSPENDED)
        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
            <i class="bi bi-pause-circle me-1"></i>Suspendu
        </span>
    @endif
@endif
