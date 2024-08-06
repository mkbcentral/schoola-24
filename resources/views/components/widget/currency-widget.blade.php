@php
    $currencies = ['USD', 'CDF'];
@endphp
<div class="row">
    @foreach ($currencies as $currency)
        <div class="col-md-2">
            <!-- radio -->
            <div class="form-group clearfix">
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
