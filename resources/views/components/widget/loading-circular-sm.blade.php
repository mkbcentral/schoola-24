@props(['action' => ''])
<span wire:loading wire:target='{{ $action }} ' {{ $attributes }}>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
</span>
