<div>
    <x-modal.build-modal-fixed idModal='payment-student-status' size='lg' headerLabel="ETAT DE PAYMENT"
        headerLabelIcon='bi bi-arrow-left-right'>
        <div class="d-flex justify-content-center" wire:loading.class="pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div wire:loading.remove>
            <div class="mb-2">
                <strong>Mois :</strong>
                <span class="badge bg-info text-dark">
                    {{ strtoupper(\Carbon\Carbon::create()->month((int) $selectedMonth)->locale('fr')->monthName) }}
                </span>
                <strong class="ms-3">Type de frais :</strong>
                <span class="badge bg-primary text-white">
                    {{ $categoryFeeSelected?->name ?? '-' }}
                </span>
                <strong class="ms-3">Classe :</strong>
                <span class="badge bg-secondary text-white">
                    {{ $classRoomSelected?->getOriginalClassRoomName() ?? '-' }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="btn-group" role="group" aria-label="Filtrer par statut de paiement">
                    <button type="button" class="btn btn-outline-primary {{ $filterStatus === 'all' ? 'active' : '' }}"
                        wire:click="$set('filterStatus', 'all')">
                        <i class="bi bi-list"></i> Tous
                    </button>
                    <button type="button"
                        class="btn btn-outline-success {{ $filterStatus === 'en_ordre' ? 'active' : '' }}"
                        wire:click="$set('filterStatus', 'en_ordre')">
                        <i class="bi bi-check-circle"></i> En ordre
                    </button>
                    <button type="button"
                        class="btn btn-outline-danger {{ $filterStatus === 'pas_en_ordre' ? 'active' : '' }}"
                        wire:click="$set('filterStatus', 'pas_en_ordre')">
                        <i class="bi bi-x-circle"></i> Pas en ordre
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3">
                        <strong>Total élèves :</strong>
                        @php
                            $total = 0;
                            foreach ($registrations as $registration) {
                                $paymentStatus = $registration->getStatusPayment(
                                    $registration->id,
                                    $selectedCategoryFeeId,
                                    $selectedMonth,
                                );
                                if ($filterStatus === 'all') {
                                    $total++;
                                } elseif ($filterStatus === 'en_ordre' && $paymentStatus) {
                                    $total++;
                                } elseif ($filterStatus === 'pas_en_ordre' && !$paymentStatus) {
                                    $total++;
                                }
                            }
                        @endphp
                        <span class="badge bg-dark">{{ $total }}</span>
                    </span>
                    <a href="#" class="btn btn-outline-secondary ms-2" onclick="window.print(); return false;">
                        <i class="bi bi-printer"></i> Imprimer
                    </a>
                </div>
            </div>
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th wire:click="sortData('students.name')" style="cursor: pointer">
                            <span>NOM COMPLET</span>
                            <x-form.sort-icon sortField="students.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($registrations->isEmpty())
                        <tr>
                            <td colspan="2"><x-errors.data-empty /></td>
                        </tr>
                    @else
                        @foreach ($registrations as $index => $registration)
                            @php
                                $paymentStatus = $registration->getStatusPayment(
                                    $registration->id,
                                    $selectedCategoryFeeId,
                                    $selectedMonth,
                                );
                                $show = false;
                                if ($filterStatus === 'all') {
                                    $show = true;
                                } elseif ($filterStatus === 'en_ordre' && $paymentStatus) {
                                    $show = true;
                                } elseif ($filterStatus === 'pas_en_ordre' && !$paymentStatus) {
                                    $show = true;
                                }
                            @endphp
                            @if ($show)
                                <tr wire:key='{{ $registration->student->id }}'>
                                    <td>{{ $registration->student->name }}/{{ substr($registration->classRoom->getOriginalClassRoomName(), 0, 8) }}
                                    </td>
                                    <td
                                        class="text-center fw-bold {{ $paymentStatus ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        {{ $paymentStatus ? 'En ordre' : '-' }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </x-modal.build-modal-fixed>
</div>
