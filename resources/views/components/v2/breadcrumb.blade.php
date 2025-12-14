@props(['items' => []])

<nav aria-label="breadcrumb" class="mb-4">
    <div class="card border-0 shadow-sm"
        style="border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="card-body py-3 px-4">
            <ol class="breadcrumb mb-0 d-flex align-items-center">
                @foreach ($items as $index => $item)
                    @if ($loop->last)
                        <li class="breadcrumb-item active d-flex align-items-center" aria-current="page">
                            <i
                                class="bi {{ $item['icon'] ?? 'bi-circle-fill' }} me-2 {{ $item['iconClass'] ?? 'text-success' }}"></i>
                            <span class="fw-bold"
                                style="color: {{ $item['color'] ?? '#198754' }};">{{ $item['label'] }}</span>
                        </li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none d-flex align-items-center"
                                style="color: #6c757d; transition: color 0.2s;" onmouseover="this.style.color='#0d6efd'"
                                onmouseout="this.style.color='#6c757d'">
                                <i class="bi {{ $item['icon'] ?? 'bi-circle-fill' }} me-2"></i>
                                <span class="fw-semibold">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
    </div>
</nav>
