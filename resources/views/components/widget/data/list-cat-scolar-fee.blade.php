@php
    $categoryFees = App\Models\CategoryFee::query()
        ->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->where('school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->get();
@endphp

<select id="my-select" class="form-control" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($categoryFees as $cat)
        <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>
