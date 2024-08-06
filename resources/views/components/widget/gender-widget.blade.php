@php
    $genders = [['k' => 'F', 'v' => 'Féminin'], ['k' => 'M', 'v' => 'Masculin']];
@endphp
<div class="row">
    @foreach ($genders as $gender)
        <div class="col-md-6">
            <!-- radio -->
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input {{ $attributes }} type="radio" value="{{ $gender['k'] }}" id="{{ $gender['k'] }}">
                    <label for="{{ $gender['k'] }}">
                        {{ $gender['v'] }}
                    </label>
                </div>
            </div>
        </div>
    @endforeach
</div>
