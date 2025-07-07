<div class=" p-4 bg-light rounded-4 shadow position-relative overflow-hidden" style="min-height: 600px;">
    {{-- Decorative background --}}
    <div
        style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;background:radial-gradient(circle at 60% 40%, #0d6efd22 60%, transparent 100%);z-index:0;">
    </div>
    <div class="row">
        <div class="col-md-4">
            {{-- Sections List --}}
            <div class="w-33 position-relative" style="z-index:1;">
                <h2 class="fs-4 fw-bold mb-4 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-list-task fs-3 text-primary"></i>
                    Sections
                </h2>
                <ul class="list-unstyled">
                    @foreach ($sections as $section)
                        <li class="mb-3">
                            <button wire:click="selectSection({{ $section->id }})"
                                class="w-100 d-flex align-items-center justify-content-between px-4 py-2 rounded-3 border-0 transition
                        {{ $selectedSection == $section->id ? 'bg-primary text-white shadow-lg scale-105' : 'bg-white text-primary-emphasis hover:bg-primary-subtle hover:shadow' }}"
                                style="transition: all 0.2s;">
                                <span class="fw-semibold">{{ $section->name }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-people-fill me-1"></i>
                                        {{ $section->options->sum(fn($option) => $option->classRooms->sum(fn($classRoom) => $classRoom->registrations()->where('abandoned', false)->count())) }}
                                    </span>
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-person-x-fill me-1"></i>
                                        {{ $section->options->sum(fn($option) => $option->classRooms->sum(fn($classRoom) => $classRoom->registrations()->where('abandoned', true)->count())) }}
                                    </span>
                                </div>
                                <i class="bi bi-chevron-right ms-2"></i>
                            </button>
                            @if ($selectedSection == $section->id)
                                <ul class="ms-4 mt-2 list-unstyled">
                                    @foreach ($section->options as $option)
                                        <li class="bg-primary-subtle rounded-3 p-3 shadow-sm mb-2 position-relative option-item"
                                            style="transition: box-shadow 0.2s;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <button wire:click="selectOption({{ $option->id }})"
                                                    class="btn btn-link p-0 text-start fw-medium transition
                                            {{ $selectedOption == $option->id ? 'text-primary text-decoration-underline' : 'text-dark hover:text-primary' }}"
                                                    style="font-size: 1.05rem;">
                                                    {{ $option->name }}
                                                </button>
                                                <div class="d-flex gap-2 small">
                                                    <span class="badge bg-success-subtle text-success shadow-sm">
                                                        <i class="bi bi-people-fill me-1"></i>
                                                        Effectif:
                                                        <b>{{ $option->classRooms->sum(fn($classRoom) => $classRoom->registrations()->where('abandoned', false)->count()) }}</b>
                                                    </span>
                                                    <span class="badge bg-danger-subtle text-danger shadow-sm">
                                                        <i class="bi bi-person-x-fill me-1"></i>
                                                        Abandons:
                                                        <b>{{ $option->classRooms->sum(fn($classRoom) => $classRoom->registrations()->where('abandoned', true)->count()) }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                            @if (isset($selectedOption) && $selectedOption == $option->id && $option->classRooms->count())
                                                <ul class="ms-4 mt-2 list-unstyled">
                                                    @foreach ($option->classRooms as $classRoom)
                                                        <li class="d-flex align-items-center justify-content-between bg-white rounded-2 px-3 py-2 shadow-sm mb-1 class-room-item"
                                                            style="transition: background 0.2s;">
                                                            <span class="fw-semibold">{{ $classRoom->name }}</span>
                                                            <div class="d-flex gap-2 small">
                                                                <span class="badge bg-success-subtle text-success">
                                                                    {{ $classRoom->registrations()->where('abandoned', false)->count() }}
                                                                </span>
                                                                <span class="badge bg-danger-subtle text-danger">
                                                                    {{ $classRoom->registrations()->where('abandoned', true)->count() }}
                                                                </span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            {{-- Liste des mois --}}
            <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                <h2 class="fs-5 fw-bold mb-3 text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-calendar3 fs-4 text-primary"></i>
                    Liste des mois
                </h2>
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mois</th>
                            <th class="text-end">RECETTE ATTENDUE</th>
                            <th class="text-end">RECETTE REALISEE</th>
                            <th class="text-end">MANQUE A GAGNER</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($months as $index => $month)
                            @php

                            @endphp
                            <tr>
                                <td>{{ $month['name'] }}</td>
                                <td class="text-end">{{ 0 }}</td>
                                <td class="text-end">{{ 0 }}</td>
                                <td class="text-end">{{ 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@push('syles')
    <style>
        <style>.option-item:hover {
            box-shadow: 0 4px 24px #0d6efd22;
            background: #e7f1ff;
        }

        .class-room-item:hover {
            background: #f0f8ff;
        }

        button.transition,
        .option-item,
        .class-room-item {
            transition: all 0.2s;
        }

        .scale-105 {
            transform: scale(1.05);
        }

        .hover\:bg-primary-subtle:hover {
            background: #e7f1ff !important;
        }

        .hover\:shadow:hover {
            box-shadow: 0 4px 24px #0d6efd22 !important;
        }

        .hover\:text-primary:hover {
            color: #0d6efd !important;
        }
    </style>

    </style>
@endpush
