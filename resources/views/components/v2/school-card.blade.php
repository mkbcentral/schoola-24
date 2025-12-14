@props(['school'])

<div class="card h-100 border-0 shadow-sm hover-card">
    <div class="card-body">
        {{-- Header with Logo and Actions --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-start grow">
                {{-- Logo --}}
                @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}"
                        alt="Logo"
                        class="rounded me-3"
                        width="48"
                        height="48"
                        style="object-fit: cover;">
                @else
                    <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3"
                        style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="bi bi-building text-primary fs-4"></i>
                    </div>
                @endif

                {{-- Name and Type --}}
                <div class="grow min-width-0">
                    <h5 class="card-title mb-1 text-truncate" title="{{ $school->name }}">
                        {{ $school->name }}
                    </h5>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary small">
                        <i class="bi bi-tag me-1"></i>{{ $school->type }}
                    </span>
                </div>
            </div>

            {{-- Actions Menu --}}
            <x-v2.school-actions-menu :school="$school" />
        </div>

        {{-- Status Badges --}}
        <div class="mb-3 d-flex flex-wrap gap-2">
            <x-v2.status-badge type="app_status" :status="$school->app_status" />
            <x-v2.status-badge type="school_status" :status="$school->school_status" />
        </div>

        {{-- Address --}}
        @if($school->address)
            <div class="mb-3 pb-3 border-bottom">
                <small class="text-muted d-flex align-items-start">
                    <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                    <span>{{ $school->address }}</span>
                </small>
            </div>
        @endif

        {{-- Contact Info --}}
        <div class="mb-3">
            <small class="d-block text-muted mb-2">
                <i class="bi bi-envelope text-primary me-2"></i>
                {{ $school->email }}
            </small>
            <small class="d-block text-muted">
                <i class="bi bi-telephone text-primary me-2"></i>
                {{ $school->phone }}
            </small>
        </div>

        {{-- Statistics --}}
        <div class="row g-2 mt-3">
            <div class="col-12">
                <div class="p-3 rounded text-center">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-people me-1"></i>Utilisateurs
                    </small>
                    <strong class="text-info fs-5">{{ $school->users_count ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card-footer bg-transparent border-0 pt-0">
        <small class="text-muted">
            <i class="bi bi-calendar3 me-1"></i>
            Créée le {{ $school->created_at->format('d/m/Y') }}
        </small>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .min-width-0 {
        min-width: 0;
    }
</style>
