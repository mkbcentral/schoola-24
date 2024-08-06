@php
    $catRegistrationFees = App\Models\CategoryRegistrationFee::query()->get();
@endphp

<select id="my-select" class="form-control" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($catRegistrationFees as $cat)
        <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>
