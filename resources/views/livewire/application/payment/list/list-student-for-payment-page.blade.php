<div>
    <livewire:application.payment.form.form-payment-page />
    <div class="card">
        <div class="card-body">
            <div class="">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <x-form.search-input wire:model.live='q' />
                        <x-form.app-button type='button' wire:click='refreshData' textButton=''
                            icon="bi bi-arrow-clockwise" class="btn-primary ms-1" />
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="">
                            <tr class="">
                                <th class="text-center">N°</th>
                                <th wire:click="sortData('students.name')" class="cursor-hand">
                                    <span>NOM COMPLET</span>
                                    <x-form.sort-icon sortField="students.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                                </th>
                                <th class="text-center">GENRE</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        @if ($registrations->isEmpty())
                            <tr>
                                <td colspan="7"><x-errors.data-empty /></td>
                            </tr>
                        @else
                            <tbody>
                                @foreach ($registrations as $index => $registration)
                                    <tr wire:key='{{ $registration->student->id }}' class=" ">
                                        <td
                                            class="text-center {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                                            {{ $index + 1 }}
                                        </td>
                                        <td>{{ $registration->student->name }}/{{ $registration?->classRoom?->getOriginalClassRoomName() }}
                                        </td>
                                        <td class="text-center">{{ $registration->student->gender }}</td>
                                        <td class="text-center">
                                            <x-form.app-button type='button' textButton="Payer"
                                                wire:click='openPaymentForm({{ $registration }})' icon=""
                                                class="btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#form-payment" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $registrations->links('livewire::bootstrap') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
