{{-- Composant Input Field - Version Tailwind CSS --}}
@props([
    'label',
    'icon',
    'type' => 'text',
    'name',
    'model',
    'placeholder' => '',
    'autocomplete' => 'off',
    'autofocus' => false,
    'disabled' => false,
    'delay' => '0.1s',
    'error' => null
])

<div class="mb-6 animate-slide-up" style="animation-delay: {{ $delay }};">
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
        <i class="bi bi-{{ $icon }}-fill mr-1"></i>
        {{ $label }}
    </label>
    <div class="relative">
        <span class="absolute left-0 inset-y-0 flex items-center pl-4 pointer-events-none @error($name) text-red-500 @else text-gray-400 dark:text-gray-500 @enderror">
            <i class="bi bi-{{ $icon == 'person' ? 'at' : 'key' }} text-lg"></i>
        </span>
        <input
            type="{{ $type }}"
            class="w-full pl-12 pr-4 py-3 border rounded-lg transition-all duration-200
                @error($name) 
                    border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-900/20 text-red-900 dark:text-red-100 placeholder-red-400 dark:placeholder-red-500 focus:ring-2 focus:ring-red-500 focus:border-transparent
                @else 
                    border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                @enderror
                disabled:opacity-50 disabled:cursor-not-allowed"
            id="{{ $name }}"
            wire:model.live.debounce.300ms="{{ $model }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if($autofocus) autofocus @endif
            @if($disabled) disabled @endif
        >
        {{ $slot }}
    </div>
    @error($name)
        <div class="mt-2 flex items-center text-red-600 dark:text-red-400 text-sm">
            <i class="bi bi-exclamation-circle-fill mr-1"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>

<style>
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out forwards;
    opacity: 0;
}
</style>
