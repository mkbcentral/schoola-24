<!-- Modal -->
@props([
    'idModal' => '',
    'headerLabelIcon' => '',
    'size' => '',
    'bg' => '',
    'headerLabel' => '',
])
<div wire:ignore.self class="modal fade " id="{{ $idModal }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $idModal }}-label" data-bs-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered" role="document" style="position:relative;">
        <div class="modal-content">
            <div class="modal-header {{ $bg }}">
                <h5 class="modal-title" id="{{ $idModal }}-label">
                    <i class="{{ $headerLabelIcon }}"></i> {{ $headerLabel }}
                </h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <!-- Resize handle -->
            <div class="resize-handle"
                style="
                position: absolute;
                width: 18px;
                height: 18px;
                right: 2px;
                bottom: 2px;
                cursor: se-resize;
                z-index: 10;
                background: transparent;
            ">
                <svg width="18" height="18">
                    <polyline points="4,18 18,18 18,4" style="fill:none;stroke:#888;stroke-width:2" />
                </svg>
            </div>
        </div>
    </div>
</div>
