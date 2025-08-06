  @props(['icon' => '', 'textButton' => '', 'tooltipText' => 'Action'])
  <button {{ $attributes->merge(['class' => 'btn']) }} data-bs-toggle="tooltip" data-bs-placement="bottom"
      data-bs-title="{{ $tooltipText }}">
      <i class="{{ $icon }}" aria-hidden="true"></i> {{ $textButton }}
  </button>
