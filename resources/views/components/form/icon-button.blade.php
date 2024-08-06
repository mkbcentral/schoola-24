@props(['icon'])
<button {{ $attributes->merge(['class' => 'btn btn-sm']) }}>
    <i class="{{$icon}}"></i>
</button>
