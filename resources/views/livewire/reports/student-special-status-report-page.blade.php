<div>
    <x-navigation.bread-crumb icon='bi bi-person-lines-fill' label="Statut spécial des élèves">
        <x-navigation.bread-crumb-item label='Rapports' />
        <x-navigation.bread-crumb-item label='Accueil' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Option</label>
                <select class="form-select shadow-sm" wire:model.live="optionId">
                    <option value="">Toutes</option>
                    @foreach ($allOptions as $opt)
                        <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Classe</label>
                <select class="form-select shadow-sm" wire:model.live="classRoomId">
                    <option value="">Toutes</option>
                    @foreach ($allClassRooms as $cr)
                        <option value="{{ $cr->id }}">{{ $cr->getOriginalClassRoomName() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <span class="fw-semibold">Total dérogations :</span>
                    <span class="ms-2">{{ $derogations->count() }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                    <i class="bi bi-cash-coin me-2"></i>
                    <span class="fw-semibold">Total exemptés :</span>
                    <span class="ms-2">{{ $exempted->count() }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-warning shadow-sm h-100">
                    <div class="card-header bg-warning text-dark fw-semibold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Élèves en dérogation
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Classe</th>
                                    <th>Option</th>
                                    <th>Durée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($derogations as $reg)
                                    <tr>
                                        <td>{{ $reg->student->name ?? '-' }}</td>
                                        <td>{{ $reg->classRoom->name ?? '-' }}</td>
                                        <td>{{ $reg->classRoom->option->name ?? '-' }}</td>
                                        <td>
                                            @if ($reg->derogation && $reg->derogation->end_date)
                                                {{ \Carbon\Carbon::parse($reg->derogation->start_date)->format('d/m/Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($reg->derogation->end_date)->format('d/m/Y') }}
                                                ({{ \Carbon\Carbon::parse($reg->derogation->start_date)->diffInDays(\Carbon\Carbon::parse($reg->derogation->end_date)) }}
                                                jours)
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Aucun élève</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="bi bi-cash-coin me-2"></i>Élèves exemptés de frais
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Classe</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exempted as $reg)
                                    <tr>
                                        <td>{{ $reg->student->name ?? '-' }}</td>
                                        <td>{{ $reg->classRoom->name ?? '-' }}</td>
                                        <td>{{ $reg->classRoom->option->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Aucun élève</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
