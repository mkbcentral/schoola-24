@props(['isLinked' => false, 'label' => '', 'link' => '', 'isFirst' => false])

@if (!$isLinked)
    <li class="flex items-center gap-2">
        @if (!$isFirst)
            <i class="bi bi-chevron-right text-gray-400 dark:text-gray-600 text-xs"></i>
        @endif
        <span class="text-gray-900 dark:text-gray-100 font-semibold">{{ $label }}</span>
    </li>
@else
    <li class="flex items-center gap-2">
        @if (!$isFirst)
            <i class="bi bi-chevron-right text-gray-400 dark:text-gray-600 text-xs"></i>
        @endif
        <a href="{{ route($link) }}" 
           class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors font-medium">
            {{ $label }}
        </a>
    </li>
@endif
