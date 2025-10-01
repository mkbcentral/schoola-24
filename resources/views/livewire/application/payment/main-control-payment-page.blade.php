<div>
    <x-navigation.bread-crumb icon='bi bi-arrow-left-right' label="Control paiement">
        <x-navigation.bread-crumb-item label='Paiements des frais' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach ($lisCategoryFee as $categoryFee)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $selectedIndex == $categoryFee->id ? 'active' : '' }}"
                                wire:click='changeIndex({{ $categoryFee->id }})' id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true"><i class="bi bi-folder"></i>
                                {{ $categoryFee->name }}</button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="d-flex justify-content-center pb-2">
                        <x-widget.loading-circular-md wire:loading />
                    </div>
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex flex-wrap gap-2">
                                <div class="d-flex align-items-center me-2">
                                    <x-form.label value="{{ __('Option') }}" class="me-2" />
                                    <x-widget.data.list-option wire:model.live='option_filter' />
                                </div>
                                <div class="d-flex align-items-center">
                                    <x-form.label value="{{ __('Classe') }}" class="me-2" />
                                    <x-widget.data.list-class-room-by-option optionId='{{ $selectedOptionId }}'
                                        wire:model.live='class_room_filter' />
                                </div>
                            </div>
                            <div class="flex-grow-1" style="max-width: 300px;">
                                <x-form.search-input wire:model.live='q' />
                            </div>
                        </div>
                        @if ($results)
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Élève</th>
                                            @if (isset($results[0]['category']))
                                                <th>Catégorie</th>
                                                <th>Statut</th>
                                            @else
                                                @foreach (['AOUT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DECEMBRE', 'JANVIER', 'FEVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN'] as $mois)
                                                    <th class="text-center">{{ $mois }}</th>
                                                @endforeach
                                                <th class="text-center">Paiement récent (4j)</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $row)
                                            <tr>
                                                <td class="{{ $row['recent_payment_status'] == true ? 'text-bg-warning' : '' }}"
                                                    style="{{ isset($row['is_under_derogation']) && $row['is_under_derogation'] == true ? 'background-color: #ffeeba;' : '' }}">
                                                    {{ $row['student'] }}
                                                </td>
                                                @if (isset($row['category']))
                                                    <td>{{ $row['category'] }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $row['status'] === 'OK' ? 'text-bg-success' : 'text-bg-danger' }}">
                                                            {{ $row['status'] }}
                                                        </span>
                                                    </td>
                                                @else
                                                    @foreach (['AOUT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DECEMBRE', 'JANVIER', 'FEVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN'] as $mois)
                                                        @php
                                                            $moisData = $row['months'][$mois] ?? [
                                                                'status' => '-',
                                                                'is_under_derogation' => false,
                                                            ];
                                                            $status = $moisData['status'] ?? '-';
                                                            $isDerog = $moisData['is_under_derogation'] ?? false;
                                                        @endphp
                                                        <td
                                                            class="text-center {{ $status === 'OK' ? 'bg-success' : ($status === '-' ? 'bg-secondary' : 'bg-danger') }} {{ $isDerog ? 'text-bg-warning' : '' }}">
                                                            {{ $status }}
                                                            @if ($isDerog)
                                                                <span
                                                                    class="badge bg-warning text-dark ms-1">Dérogation</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td
                                                        class="{{ $row['recent_payment_status'] == true ? 'text-bg-warning' : '' }} text-end">
                                                        @if ($row['recent_payment_status'] == true)
                                                            <span class="badge text-bg-light">Oui</span>
                                                            {{ $row['interval_label'] }}
                                                        @else
                                                            <span>
                                                                -
                                                            </span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
