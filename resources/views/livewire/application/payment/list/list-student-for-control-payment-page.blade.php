<div>
    @php
        $months = App\Domain\Helpers\DateFormatHelper::getSchoolFrMonths();
        $paymentStatus = false;
    @endphp
    <div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex flex-column flex-md-row">
                <div class="d-flex align-items-center me-2 mb-2 mb-md-0">
                    <x-form.label value="{{ __('Option') }}" class="me-2" />
                    <x-widget.data.list-option wire:model.live='option_filter' />
                </div>
                <div class="d-flex align-items-center">
                    <x-form.label value="{{ __('Classe') }}" class="me-2" />
                    <x-widget.data.list-class-room-by-option optionId='{{ $selectedOptionId }}'
                        wire:model.live='class_room_filter' />
                </div>
            </div>
            <div class="mt-2 mt-md-0">
                <x-form.search-input wire:model.live='q' />
            </div>
        </div>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-2">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">N°</th>
                        <th wire:click="sortData('students.name')" class="cursor-hand">
                            <span>NOM COMPLET</span>
                            <x-form.sort-icon sortField="students.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        @foreach ($months as $month)
                            @php
                                $formattedMonth = substr($month['name'], 0, 4);
                            @endphp
                            <th class="text-center" style="cursor: pointer" data-bs-toggle="modal"
                                data-bs-target="#payment-student-tranch-status"
                                wire:click="openListStatusDetails({{ $month['number'] }})">
                                {{ $formattedMonth }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if ($registrations->isEmpty())
                        <tr>
                            <td colspan="12"><x-errors.data-empty /></td>
                        </tr>
                    @else
                        @foreach ($registrations as $index => $registration)
                            <tr wire:key='{{ $registration->student->id }}'>
                                <td class="text-center {{ $registration->abandoned ? 'bg-warning' : '' }}">
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    {{ $registration->student->name }}/{{ substr($registration->classRoom->getOriginalClassRoomName(), 0, 8) }}
                                </td>
                                @foreach ($months as $month)
                                    @php
                                        $paymentStatus = $registration->getStatusPayment(
                                            $registration->id,
                                            $selectedCategoryFeeId,
                                            App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
                                            $month['number'],
                                        );
                                    @endphp
                                    <td
                                        class="text-center fw-bold {{ $paymentStatus ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        {{ $paymentStatus ? 'OK' : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
