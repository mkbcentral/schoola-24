@php
    $options = App\Models\Option::query()
        ->join('sections', 'options.section_id', 'sections.id')
        ->select('options.*')
        ->where('sections.school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->get();
@endphp

<select id="my-select" class="form-select" {{ $attributes }}>
    <option value="0">Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($options as $option)
        <option class="text-uppercase" value="{{ $option->id }}">{{ $option->name }}</option>
    @endforeach
</select>
