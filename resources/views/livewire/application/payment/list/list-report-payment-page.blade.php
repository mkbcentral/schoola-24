<div>
    <div class="container-fluid px-0">
        <div>
            <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-md-center mb-2 p-2">
                <h3 class="text-primary text-uppercase mb-2 mb-md-0 ms-md-auto">
                    Total: {{ app_format_number($total_payments, 1) }} {{ $categoryFeeSelected?->currency }}
                </h3>
            </div>
            <div class="d-flex flex-wrap gap-2 p-2">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center me-2">
                    <x-form.label value="{{ __('Date') }}" class="me-2 mb-1 mb-sm-0" />
                    <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" class="w-100 w-sm-auto" />
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center me-2">
                    <x-form.label value="{{ __('Mois') }}" class="me-2 mb-1 mb-sm-0" />
                    <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" class="w-100 w-sm-auto" />
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center me-2">
                    <x-form.label value="{{ __('Section') }}" class="me-2 mb-1 mb-sm-0" />
                    <x-widget.data.list-section wire:model.live='section_filter' class="w-100 w-sm-auto" />
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center me-2">
                    <x-form.label value="{{ __('Option') }}" class="me-2 mb-1 mb-sm-0" />
                    <x-widget.data.list-option-by-section sectionId='{{ $selectedSection }}'
                        wire:model.live='option_filter' class="w-100 w-sm-auto" />
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center me-2">
                    <x-form.label value="{{ __('Classe') }}" class="me-2 mb-1 mb-sm-0" />
                    <x-widget.data.list-class-room-by-option optionId='{{ $selectedOption }}'
                        wire:model.live='class_room_filter' class="w-100 w-sm-auto" />
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center me-2">
                    <x-form.label value="{{ __('Frais') }}" class="me-2 mb-1 mb-sm-0" />
                    <x-widget.data.list-fee-by-category selectedCategoryId='{{ $selectedCategoryFeeId }}'
                        classRoomId='{{ $selectedClassRoom }}' wire:model.live='scolary_fee_filter'
                        class="w-100 w-sm-auto" />
                </div>
                <div class="d-flex align-items-center">
                    <x-others.dropdown wire:ignore.self icon="bi bi-printer-fill" title='Impression'
                        class="btn-outline-secondary btn-sm ms-2">
                        @if ($isByDate == true)
                            <x-others.dropdown-link iconLink='bi bi-printer-fill'
                                labelText='Imprimer rapport journalier'
                                href="{{ route('print.payment.date', [
                                    $date_filter,
                                    $selectedCategoryFeeId,
                                    $scolary_fee_filter,
                                    $section_filter,
                                    $selectedOption,
                                    $class_room_filter,
                                ]) }}"
                                target='_blank' />
                            <x-others.dropdown-link iconLink='bi bi-printer-fill'
                                labelText='Bordereau de versement journalier'
                                href="{{ route('print.payment.slip.date', [$date_filter]) }}" target='_blank' />
                        @else
                            <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer rapport mensuel'
                                href="{{ route('print.payment.month', [
                                    $month_filter,
                                    $selectedCategoryFeeId,
                                    $scolary_fee_filter,
                                    $section_filter,
                                    $selectedOption,
                                    $class_room_filter,
                                ]) }}"
                                target='_blank' />
                            <x-others.dropdown-link iconLink='bi bi-printer-fill'
                                labelText='Bordereau de versement mensuel'
                                href="{{ route('print.payment.slip.month', [$month_filter]) }}" target='_blank' />
                        @endif
                    </x-others.dropdown>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div class="table-responsive mt-2">
            <table class="table table-bordered table-sm table-hover mb-0" wire:loading.class='d-none'>
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">N°</th>
                        <th>DATE PAIMENT</th>
                        <th>CODE</th>
                        <th class="cursor-hand"><span>NOM COMPLET</span></th>
                        <th>CLASSE</th>
                        <th>FRAIS</th>
                        <th class="text-end">MONTANT</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                @if ($payments->isEmpty())
                    <tr>
                        <td colspan="8"><x-errors.data-empty /></td>
                    </tr>
                @else
                    <tbody>
                        @foreach ($payments as $index => $payment)
                            <tr wire:key='{{ $payment->id }}'>
                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $payment->created_at->format('d/m/Y') }}
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
                                <td class="text-center">
                                    <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical" class="btn-sm">
                                        <x-others.dropdown-link iconLink='bi bi-send-plus' labelText='Envoyer SMS'
                                            wire:confirm="Etês-vous sûre d'envoyer l'SMS" href="#"
                                            wire:click='sendSMS({{ $payment }})' class="text-primary" />
                                    </x-others.dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-2 gap-2">
            <span>{{ $payments->links('livewire::bootstrap') }}</span>
            @if ($payments->count() > 9)
                <x-others.table-page-number wire:model.live='per_page' />
            @endif
        </div>
    </div>
</div>
