<div>
    @props(['id'])

    <div class="input-group">
        <div class="input-group-append">
            <div class="input-group-text"><i class="far fa-clock"></i></div>
        </div>
        <input type="text" {{ $attributes }} id="{{ $id }}" data-toggle="datetimepicker"
            data-target="#{{ $id }}" class="form-control datetimepicker-input"
            onchange="this.dispatchEvent(new InputEvent('input'))" />
    </div>
    @push('js')
        <script type="module">
            $(function() {
                $('#{{ $id }}').datetimepicker({
                    format: 'LT',
                    local: 'fr'
                });
            });
        </script>
    @endpush
</div>
