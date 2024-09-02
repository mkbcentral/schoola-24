@props(['optionId' => 0])
@php
    $sections = App\Models\Section::query()->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())->get();
@endphp

<select id="my-select" class="form-control" {{ $attributes }}>
    <option disabled>Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($sections as $section)
        <option class="text-uppercase" value="{{ $section->id }}">{{ $section->name }}</option>
    @endforeach
</select>
