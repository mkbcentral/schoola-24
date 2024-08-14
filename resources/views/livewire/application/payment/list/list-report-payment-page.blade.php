<div>
    <div>
        <div class="d-flex justify-content-end">
            <h3 class="text-primary text-uppercase">Total: {{ app_format_number($total_payments, 1) }}
                {{ $categoryFeeSelected->currency }}</h3>
        </div>
        <div class="d-flex">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Date') }}" class="me-2" />
                <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
            </div>
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Mois') }}" class="me-2" />
                <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
            </div>
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Section') }}" class="me-2" />
                <x-widget.data.list-section wire:model.live='section_filter' />
            </div>
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Option') }}" class="me-2" />
                <x-widget.data.list-option-by-section sectionId='{{ $selectedSection }}'
                    wire:model.live='option_filter' />
            </div>
            <div class="d-flex align-items-center">
                <x-form.label value="{{ __('Classe') }}" class="me-2" />
                <x-widget.data.list-class-room-by-option optionId='{{ $selectedOption }}'
                    wire:model.live='class_room_filter' />
            </div>
            <div class="d-flex align-items-center">
                <x-form.label value="{{ __('Frais') }}" class="me-2" />
                <x-widget.data.list-fee-by-category selectedCategoryId='{{ $selectedCategoryFeeId }}'
                    classRoomId='{{ $selectedClassRoom }}' wire:model.live='scolary_fee_filter' />
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <table class="table table-bordered table-sm table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th class="">CODE</th>
                <th class="cursor-hand">
                    <span>NOM COMPLET</span>
                </th>
                <th>CLASSE</th>
                <th>FRAIS</th>
                <th class="text-end">MONTANT</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($payments->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($payments as $index => $payment)
                    <tr wire:key='{{ $payment->id }}' class=" ">
                        <td class="text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="text-uppercase">
                            {{ $payment->payment_number }}
                        </td>
                        <td>{{ $payment->registration->student->name }}</td>
                        <td>{{ $payment?->registration->classRoom?->getOriginalClassRoomName() }}</td>
                        <td>
                            {{ $payment->scolarFee->name }}/
                            {{ format_fr_month_name($payment->month) }}
                        </td>
                        <td class="text-end">{{ app_format_number($payment->getAmount(), 1) }}
                            {{ $payment->scolarFee->categoryFee->currency }}</td>
                        <td class="text-center"></td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <span>{{ $payments->links('livewire::bootstrap') }}</span>
        @if ($payments->count() > 9)
            <x-others.table-page-number wire:model.live='per_page' />
        @endif
    </div>
</div>
