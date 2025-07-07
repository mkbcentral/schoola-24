<div>
    @php
        $paymentStatus = false;
    @endphp
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex">
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
        <x-form.search-input wire:model.live='q' />
    </div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <table class="table table-bordered table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th wire:click="sortData('students.name')" class="cursor-hand">
                    <span>NOM COMPLET</span>
                    <x-form.sort-icon sortField="students.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                </th>
                @foreach ($scolarFees as $scolarFee)
                    <th class="text-ellipsis" style="cursor: pointer" data-bs-toggle="modal"
                        data-bs-target="#payment-student-status"
                        wire:click="openListStatusDetails({{ $scolarFee->id }})">
                        {{ $scolarFee->name }}</th>
                @endforeach

            </tr>
        </thead>
        @if ($registrations->isEmpty())
            <tr>
                <td colspan="12"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($registrations as $index => $registration)
                    <tr wire:key='{{ $registration->student->id }}' class=" ">

                        <td class="text-center {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $registration->student->name }}/{{ $registration->classRoom->getOriginalClassRoomName() }}
                        </td>
                        @foreach ($scolarFees as $scolarFee)
                            @php
                                $paymentStatus = $registration->getStatusPaymentByTranch(
                                    $registration->id,
                                    $selectedCategoryFeeId,
                                    $scolarFee->id,
                                );
                            @endphp
                            <td
                                class="text-center fw-bold {{ $paymentStatus ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                {{ $paymentStatus ? 'En ordre' : '-' }}
                            </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</div>
