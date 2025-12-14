@props(['sections', 'options', 'classRooms', 'sectionId', 'optionId'])

<div class="col-12">
    <label class="form-label fw-bold">
        <i class="bi bi-diagram-3"></i> Section
    </label>
    <select wire:model.live="sectionId" class="form-select">
        <option value="">Toutes les sections</option>
        @foreach ($sections as $section)
            <option value="{{ $section->id }}">{{ $section->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-12">
    <label class="form-label fw-bold">
        <i class="bi bi-bookmark"></i> Option
    </label>
    <select wire:model.live="optionId" class="form-select" {{ !$sectionId ? 'disabled' : '' }}>
        <option value="">
            {{ $sectionId ? 'Toutes les options' : 'Sélectionnez d\'abord une section' }}
        </option>
        @foreach ($options as $option)
            <option value="{{ $option->id }}">{{ $option->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-12">
    <label class="form-label fw-bold">
        <i class="bi bi-door-open"></i> Classe
    </label>
    <select wire:model.live="classRoomId" class="form-select" {{ !$optionId ? 'disabled' : '' }}>
        <option value="">
            {{ $optionId ? 'Toutes les classes' : 'Sélectionnez d\'abord une option' }}
        </option>
        @foreach ($classRooms as $classRoom)
            <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
        @endforeach
    </select>
</div>
