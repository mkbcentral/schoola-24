@php
      $categoryFees = \App\Domain\Features\Configuration\FeeDataConfiguration::getListCategoryFeeForSpecificUser(00);
@endphp

<select id="my-select" class="form-select form-control" {{ $attributes }}>
    <option disabled>Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($categoryFees as $cat)
        <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>
