<div wire:ignore.self>
    <div class="card  card-indigo p-4">
        <div>
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-uppercase"><i class="fa fa-list" aria-hidden="true"></i>
                    Liste des frais d'inscription
                </h5>
                <x-form.app-button wire:click='newFee' data-bs-toggle="modal" data-bs-target="#form-scolar-fee"
                    textButton='Nouveau frais' icon="bi bi-plus-circle" class="app-btn" />
            </div>
            <div class="mt-2">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <x-form.search-input wire:model.live='q' />
                    <div class="d-flex align-items-center ">
                        <div class="d-flex align-items-center me-4">
                            <x-form.label value="{{ __('Option') }}" class=" me-2" />
                            <x-widget.data.list-option type='text' wire:model.live='option_filter'
                                :error="'option_filter'" />
                        </div>
                        <div class="d-flex align-items-center ">
                            <x-form.label value="{{ __('Classe') }}" class=" me-2" />
                            <x-widget.data.list-class-room-by-option optionId="{{ $option_filter }}" type='text'
                                wire:model.live='class_room_filter' :error="'class_room_filter'" />
                        </div>
                        <x-form.app-button type='button' wire:click='refreshData' textButton=''
                            icon="bi bi-arrow-clockwise" class="app-btn ms-1" />
                    </div>
                </div>
                <table class="table table-bordered table-hover table-sm mt-2">
                    <thead class="bg-app">
                        <tr class="cursor-hand">
                            <th class="text-center">#</th>
                            <th>NOM FRAIS</th>
                            <th class="">CLASSE</th>
                            <th class="text-end">MONTANT</th>
                            <th class="text-end">DEVISE</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($scolrFees->isEmpty())
                            <tr>
                                <td colspan="6"><x-errors.data-empty /></td>
                            </tr>
                        @else
                            @foreach ($scolrFees as $index => $scolarFee)
                                <tr wire:key='{{ $scolarFee->id }}'>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $scolarFee->name }}</td>
                                    <td class="">{{ $scolarFee?->classRoom->getOriginalClassRoomName() }}</td>
                                    <td class="text-end">{{ $scolarFee->amount }}</td>
                                    <td class="text-end">{{ $scolarFee->currency }}</td>
                                    <td class="text-center">
                                        <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                            data-bs-toggle="modal" data-bs-target="#form-scolar-fee"
                                            class="btn-sm app-btn" wire:click='edit({{ $scolarFee }})' />
                                        <x-form.app-button wire:confirm="Est-vous sur de réaliser l'opération"
                                            type='button' icon="bi bi-trash-fill" class="btn-secondary btn-sm"
                                            wire:click='delete({{ $scolarFee }})' />
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $scolrFees->links('livewire::bootstrap') }}</span>
                </div>
            </div>
        </div>
    </div>
    <livewire:application.fee.scolar.form.form-scolar-fee-page>
</div>
