@php
    $months = App\Domain\Helpers\DateFormatHelper::getFrMonths();
@endphp
@props(['error'])
<select {{ $attributes }} class="form-control  @error($error) is-invalid @enderror">
    <option disabled>Choisir</option>
    <option value="">Tout</option>
    @foreach ($months as $month)
        <option value="{{ $month['number'] }}">{{ $month['name'] }}</option>
    @endforeach
</select>
