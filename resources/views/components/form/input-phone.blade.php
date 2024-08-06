<div>
    @props(['mask', 'error'])
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="bi bi-telephone-fill"></i>
            </span>
        </div>
        <input {{ $attributes }} type="text"
            class="form-control phone-mask @error($error) is-invalid
             @enderror" id="phone-mask"
            placeholder="(+243) ___-___-___" onchange="this.dispatchEvent(new InputEvent('input'))">
    </div>
    @push('js')
        <script type="module">
            $(function() {
                $('.phone-mask').mask('(+243) 000-000-000')
            });
        </script>
    @endpush
</div>
