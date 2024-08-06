@php
    $value = 5;
@endphp
<div class="d-flex align-items-center">
    <span>Nbre/page</span>
    <select class="form-control w-20 ml-2" {{ $attributes }}>
        @for ($i = 0; $i < 5; $i++)
            @php
                $value += 5;
            @endphp
            <option value="{{ $value }}">{{ $value }}</option>
        @endfor
    </select>
</div>
