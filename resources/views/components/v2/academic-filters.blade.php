@props(['sections', 'options', 'classRooms', 'sectionId', 'optionId'])

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            <i class="bi bi-diagram-3"></i> Section
        </label>
        <select wire:model.live="sectionId" 
                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            <option value="">Toutes les sections</option>
            @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            <i class="bi bi-bookmark"></i> Option
        </label>
        <select wire:model.live="optionId" 
                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:opacity-50 disabled:cursor-not-allowed" 
                {{ !$sectionId ? 'disabled' : '' }}>
            <option value="">
                {{ $sectionId ? 'Toutes les options' : 'Sélectionnez d\'abord une section' }}
            </option>
            @foreach ($options as $option)
                <option value="{{ $option->id }}">{{ $option->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            <i class="bi bi-door-open"></i> Classe
        </label>
        <select wire:model.live="classRoomId" 
                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:opacity-50 disabled:cursor-not-allowed" 
                {{ !$optionId ? 'disabled' : '' }}>
            <option value="">
                {{ $optionId ? 'Toutes les classes' : 'Sélectionnez d\'abord une option' }}
            </option>
            @foreach ($classRooms as $classRoom)
                <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
            @endforeach
        </select>
    </div>
</div>
