@props(['optionId' => 0, 'isOld' => false])
@php
    $registrationFees = App\Models\RegistrationFee::query()
        ->join(
            'category_registration_fees',
            'registration_fees.category_registration_fee_id',
            '=',
            'category_registration_fees.id',
        )
        ->join('options', 'registration_fees.option_id', '=', 'options.id')
        ->join('sections', 'options.section_id', '=', 'sections.id')
        ->where('sections.school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->where('options.id', $optionId)
        ->where('category_registration_fees.is_old', $isOld == '' ? 0 : $isOld)
        ->select('registration_fees.*')
        ->get();
@endphp
<select id="my-select" class="form-control" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($registrationFees as $registrationFee)
        <option class="text-uppercase" value="{{ $registrationFee->id }}">{{ $registrationFee->name }}</option>
    @endforeach
</select>
