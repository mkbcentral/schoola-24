@props(['error'])

@php
    $listResp = \App\Models\ResponsibleStudent::orderBy('name', 'asc')->get();
@endphp

<div>
    <div wire:ignore>
        <select {{ $attributes }} class="form-control mb-2 selectrResp @error($error) is-invalid @enderror">
            <option value="">Choisir</option>
            @foreach ($listResp as $resp)
                <option class="text-uppercase" value="{{ $resp->id }}">{{ $resp->name }}</option>
            @endforeach
        </select>
        @error($error)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @push('js')
        <script type="module">
            $(function() {
                $('.selectrResp').select2({
                    theme: "bootstrap-5",
                    containerCssClass: "select2--small",
                    dropdownCssClass: "select2--small",
                }).on('change', function(e) {
                    @this.set('responsible_student_id', e.target.value);
                });
            });
        </script>
    @endpush
</div>
