@php
    $otherSource = App\Models\OtherSourceExpense::query()
        ->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->get();
@endphp

<select id="my-select" class="form-select form-control" {{ $attributes }}>
    <option disabled>Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($otherSource as $other)
        <option class="text-uppercase" value="{{ $other->id }}">{{ $other->name }}</option>
    @endforeach
</select>
