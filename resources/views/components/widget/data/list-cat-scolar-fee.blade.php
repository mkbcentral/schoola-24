@php
    $categoryFees = App\Models\CategoryFee::query()
        ->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->where('school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->get();
@endphp

<select id="my-select" class="form-select form-control" {{ $attributes }}>
    <option disabled>Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($categoryFees as $cat)
        <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>
