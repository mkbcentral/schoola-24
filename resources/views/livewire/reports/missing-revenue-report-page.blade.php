<div>
    <div class="d-flex align-items-center mb-4">
        <h2 class="fs-2 fw-bold mb-0 me-3 text-primary-emphasis">Manque à Gagner - Rapport</h2>
    </div>
    <div class="d-flex justify-content-center mb-3">
        <div wire:loading.delay>
            <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
            <span class="ms-2 text-primary">Chargement...</span>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <label for="option" class="form-label fw-semibold">Option</label>
            <select wire:model.live="optionId" id="option" class="form-select shadow-sm border-primary-subtle">
                <option value="">-- Choisir une option --</option>
                @foreach ($allOptions as $option)
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label fw-semibold">Catégorie de frais</label>
            <select wire:model.live="categoryFeeId" id="category" class="form-select shadow-sm border-primary-subtle">
                <option value="">-- Choisir une catégorie --</option>
                @foreach ($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="month" class="form-label fw-semibold">Mois (optionnel)</label>
            <select wire:model.live="month" id="month" class="form-select shadow-sm border-primary-subtle">
                <option value="">-- Choisir un mois --</option>
                <option value="1">Janvier</option>
                <option value="2">Février</option>
                <option value="3">Mars</option>
                <option value="4">Avril</option>
                <option value="5">Mai</option>
                <option value="6">Juin</option>
                <option value="7">Juillet</option>
                <option value="8">Août</option>
                <option value="9">Septembre</option>
                <option value="10">Octobre</option>
                <option value="11">Novembre</option>
                <option value="12">Décembre</option>
            </select>
        </div>
    </div>
    @if ($results && !empty($results['by_class']))
        <div class="mb-5">
            <h3 class="fw-bold fs-4 mb-3 text-primary-emphasis">Résumé Global</h3>
            <div class="bg-primary bg-opacity-10 rounded-4 p-4 row g-3 shadow">
                <div class="col-md-2"><span class="fw-semibold">Option:</span> {{ $results['global']['option'] }}</div>
                <div class="col-md-2"><span class="fw-semibold">Total attendu:</span>
                    <span class="text-primary">{{ number_format($results['global']['total_expected'], 0, ',', ' ') }}
                        {{ $currency }}</span>
                </div>
                <div class="col-md-2"><span class="fw-semibold">Total payé:</span>
                    <span class="text-success">{{ number_format($results['global']['total_paid'], 0, ',', ' ') }}
                        {{ $currency }}</span>
                </div>
                <div class="col-md-3"><span class="fw-semibold">Manque à gagner:</span>
                    <span
                        class="text-danger fw-bold">{{ number_format($results['global']['missing_revenue'], 0, ',', ' ') }}
                        {{ $currency }}</span>
                </div>
                <div class="col-md-1"><span class="fw-semibold">Élèves:</span>
                    <span class="badge text-bg-secondary">{{ $results['global']['student_count'] }}</span>
                </div>
                <div class="col-md-2"><span class="fw-semibold">Ayant payé:</span>
                    <span class="badge text-bg-success bg-opacity-75">{{ $results['global']['paid_count'] }}</span>
                </div>
            </div>
        </div>
        <div>
            <h3 class="fw-bold fs-4 text-primary-emphasis">Détail par classe</h3>
            <div class="table-responsive rounded-4 shadow">
                <table class="table table-bordered table-hover align-middle mb-0 bg-body">
                    <thead class="table-primary">
                        <tr>
                            <th>Classe</th>
                            <th class="text-end">Attendu</th>
                            <th class="text-end">Payé</th>
                            <th class="text-end">Manque à gagner</th>
                            <th class="text-end">Élèves attendus</th>
                            <th class="text-end">Ayant payé</th>
                            <th class="text-end">Reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results['by_class'] as $row)
                            <tr
                                @if ($unpaidClass === $row['class_room']) style="background:rgba(220,53,69,0.07);transition:background 0.4s;" @endif>
                                <td class="fw-semibold">
                                    {{ $row['class_room'] }}
                                    @if (isset($results['global']['option']))
                                        <span class="text-muted small">/{{ $results['global']['option'] }}</span>
                                    @endif
                                </td>
                                <td class="text-end text-primary">
                                    {{ number_format($row['total_expected'], 0, ',', ' ') }} {{ $currency }}</td>
                                <td class="text-end text-success">{{ number_format($row['total_paid'], 0, ',', ' ') }}
                                    {{ $currency }}</td>
                                <td class="text-danger fw-bold text-end">
                                    {{ number_format($row['missing_revenue'], 0, ',', ' ') }} {{ $currency }}</td>
                                <td class="text-end">
                                    <span class="badge text-bg-secondary"
                                        title="Nombre d'élèves attendus pour le calcul du manque à gagner">
                                        {{ $row['student_count'] }} attendus
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="badge text-bg-success bg-opacity-75"
                                        title="Nombre d'élèves ayant payé">
                                        {{ $row['paid_count'] }} payés
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="badge text-bg-info  shadow-sm"
                                        title="Effectif réel de la classe (tous inscrits)"
                                        style="cursor:pointer;transition:box-shadow 0.3s;"
                                        wire:click="showUnpaidList('{{ $row['class_room'] }}')">
                                        {{ $row['student_count'] - $row['paid_count'] }} effectif
                                        @if ($unpaidClass === $row['class_room'])
                                            <i class="bi bi-caret-down-fill ms-1"></i>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @if ($unpaidClass === $row['class_room'])
                                <tr>
                                    <td colspan="7" class="p-0 border-0">
                                        <div class="alert alert-danger rounded-3 shadow-sm fade show mb-0 py-3 px-4 animate__animated animate__fadeInDown"
                                            role="alert" style="border: none;">
                                            <div class="fw-bold mb-2">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                Élèves non en ordre - {{ $row['class_room'] }}
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span
                                                        class="badge text-bg-secondary ms-2">{{ count($unpaidList) }}</span>
                                                    <span>Mois: {{ format_fr_month_name($month) }}</span>
                                                </div>
                                            </div>
                                            @if (!empty($unpaidList) && count($unpaidList) > 0)
                                                <ul class="list-group list-group-flush mb-0">
                                                    @foreach ($unpaidList as $i => $student)
                                                        <li
                                                            class="list-group-item d-flex align-items-center py-2 bg-body">
                                                            <span
                                                                class="badge bg-secondary me-2">{{ $i + 1 }}</span>
                                                            <span
                                                                class="fw-semibold me-3 text-body">{{ $student['matricule'] }}</span>
                                                            <span class="text-body">{{ $student['name'] }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div
                                                    class="alert alert-success rounded-2 py-2 px-3 mb-0 d-inline-block">
                                                    Tous les élèves de cette classe sont en ordre.</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($optionId && $categoryFeeId)
        <div class="text-secondary mt-4 text-center">
            <i class="bi bi-info-circle me-2"></i>
            Aucune donnée trouvée pour les filtres sélectionnés.
        </div>
    @endif
    @push('scripts')
        <script>
            function toggleUnpaidList(classRoom) {
                console.log("Toggling unpaid list for class:", classRoom);
                window.livewire.emit('showUnpaidList', classRoom);
            }
        </script>
    @endpush

    {{-- Styles dark mode intégrés dans themes/_dark.scss --}}
</div>
