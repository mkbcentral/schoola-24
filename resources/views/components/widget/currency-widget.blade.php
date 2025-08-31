@props(['size' => 'col-md-2'])
@php
    $currencies = ['USD', 'CDF'];
@endphp
<div class="row">
    @foreach ($currencies as $currency)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 {{ $size }} mb-2">
            <!-- radio -->
            <div class="form-group clearfix m-0">
                <div class="icheck-primary d-inline">
                    <input {{ $attributes }} type="radio" value="{{ $currency }}" id="{{ $currency }}">
                    <label for="{{ $currency }}">
                        {{ $currency }}
                    </label>
                </div>
            </div>
        </div>
    @endforeach
</div>
