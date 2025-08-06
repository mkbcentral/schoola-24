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
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header {{ $bg }}">
                <h5 class="modal-title" id="{{ $idModal }}-label">
                    <i class="{{ $headerLabelIcon }}"></i> {{ $headerLabel }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
@push('js')
    <script type="module">
        var modalElement = document.getElementById('{{ $idModal }}');
        var modal = new bootstrap.Modal(modalElement);

        modalElement.addEventListener('hidden.bs.modal', function() {
            modalElement.setAttribute('inert', 'true');
        });

        modalElement.addEventListener('shown.bs.modal', function() {
            modalElement.removeAttribute('inert');
        });
    </script>
@endpush
