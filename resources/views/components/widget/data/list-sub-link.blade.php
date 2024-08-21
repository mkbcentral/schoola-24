@php
    $multiAppLinks = App\Models\MultiAppLink::query()->get();
@endphp
<select id="my-select" class="form-control" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($multiAppLinks as $multiAppLink)
        <option class="text-uppercase" value="{{ $multiAppLink->id }}">{{ $multiAppLink->name }}</option>
    @endforeach
</select>
