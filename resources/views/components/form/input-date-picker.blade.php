<div>
    @props(['id', 'error'])
    <div class="input-group">
        <div class="input-group-append">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
        <input type="text" {{ $attributes }} id="{{ $id }}" data-toggle="datetimepicker"
            data-target="#{{ $id }}"
            class="form-control datetimepicker-input  @error($error) is-invalid @enderror "
            onchange="this.dispatchEvent(new InputEvent('input'))" />
    </div>
    @push('js')
        <script type="module">
            $(function() {
                $('#{{ $id }}').datetimepicker({
                    format: 'L',
                });
            });
        </script>
    @endpush
</div>
