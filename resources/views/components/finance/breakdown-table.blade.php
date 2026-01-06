{{-- Composant de Tableau de Ventilation Moderne --}}
@props([
    'title' => '',
    'icon' => 'table',
    'headers' => [],
    'maxHeight' => null,
    'striped' => true,
    'hoverable' => true
])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden" {{ $attributes }}>
    {{-- En-tête --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
        <h3 class="text-white font-bold text-lg flex items-center gap-2">
            <i class="bi bi-{{ $icon }}"></i>
            {{ $title }}
        </h3>
    </div>
    
    {{-- Corps du tableau --}}
    <div class="p-6">
        <div class="overflow-x-auto {{ $maxHeight ? 'overflow-y-auto' : '' }}" @if($maxHeight) style="max-height: {{ $maxHeight }};" @endif>
            <table class="w-full">
                @if (!empty($headers))
                    <thead class="{{ $maxHeight ? 'sticky top-0 bg-gray-50 dark:bg-gray-900' : '' }}">
                        <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                            @foreach ($headers as $header)
                                <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 {{ $header['class'] ?? '' }}">
                                    {{ $header['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody class="{{ $striped ? 'divide-y divide-gray-100 dark:divide-gray-700' : '' }}">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>
