@php
    $categoryExpenses = App\Models\CategoryExpense::query()
        ->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->get();
@endphp
<select id="my-select" class="form-select form-control" {{ $attributes }}>
    <option disabled>Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($categoryExpenses as $cat)
        <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>
