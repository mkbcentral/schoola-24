@php
    $months = App\Domain\Helpers\DateFormatHelper::getFrMonths();
@endphp
@props(['error' => null])
<select
    {{ $attributes->merge(['class' => 'form-select form-control' . ($error && $errors->has($error) ? ' is-invalid' : '')]) }}>
    <option disabled>Choisir</option>
    <option value="">Tout</option>
    @foreach ($months as $month)
        <option value="{{ $month['number'] }}">{{ $month['name'] }}</option>
    @endforeach
</select>
