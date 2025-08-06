@php
    $genders = [['k' => 'F', 'v' => 'Féminin'], ['k' => 'M', 'v' => 'Masculin']];
@endphp
<div class="row">
    @foreach ($genders as $gender)
        <div class="col-md-6">
            <!-- radio -->
            <div class="form-group clearfix" style="margin-bottom: 1rem;">
                <div class="icheck-primary d-inline" style="display: flex; align-items: center;">
                    <input {{ $attributes }} type="radio" value="{{ $gender['k'] }}" id="{{ $gender['k'] }}"
                        style="margin-right: 8px;">
                    <label for="{{ $gender['k'] }}" style="font-weight: 500; font-size: 1.1rem; cursor: pointer;">
                        {{ $gender['v'] }}
                    </label>
                </div>
            </div>
        </div>
    @endforeach
</div>
