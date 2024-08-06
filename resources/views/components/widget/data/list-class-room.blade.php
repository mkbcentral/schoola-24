@props(['optionId' => 0])
@php
    $classRooms = App\Models\ClassRoom::query()
        ->join('options', 'class_rooms.option_id', '=', 'options.id')
        ->join('sections', 'options.section_id', '=', 'sections.id')
        ->where('sections.school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->select('class_rooms.*')
        ->get();
@endphp

<select id="my-select" class="form-select" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($classRooms as $classRoom)
        <option class="text-uppercase" value="{{ $classRoom->id }}">
            {{ $classRoom->getOriginalClassRoomName() }}</option>
    @endforeach
</select>
