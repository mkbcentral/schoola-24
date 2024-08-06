@php
    $registrationFees = App\Models\RegistrationFee::query()
        ->join('options', 'registration_fees.option_id', '=', 'options.id')
        ->join('sections', 'options.section_id', '=', 'sections.id')
        ->where('sections.school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->select('registration_fees.*')
        ->get();
@endphp

<select id="my-select" class="form-control" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($registrationFees as $registrationFee)
        <option class="text-uppercase" value="{{ $registrationFee->id }}">{{ $registrationFee->name }}</option>
    @endforeach
</select>
