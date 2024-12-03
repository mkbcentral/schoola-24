<div>
    @php
        $months = App\Domain\Helpers\DateFormatHelper::getScoolFrMonths();
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
                @foreach ($months as $month)
                    <th class="text-center">{{ $month['name'] }}</th>
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

                        <td class="text-center {{ $registration->abandoned ? 'bg-warning' : '' }}">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $registration->student->name }}/{{ substr($registration->classRoom->getOriginalClassRoomName(),0,8) }}
                        </td>
                        @foreach ($months as $month)
                            @php
                                $paymentStatus = $registration->getStatusPayment(
                                    $registration->id,
                                    $selectedCategoryFeeId,
                                    $month['number'],
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
