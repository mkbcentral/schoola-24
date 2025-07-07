@push('style')
    <style>
        .info-card {
            max-width: 540px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
@php
    $months = collect(App\Domain\Helpers\DateFormatHelper::getSchoolFrMonths())
        ->reject(fn($month) => in_array(strtoupper($month['name']), ['JUILLET', 'AOUT']))
        ->values()
        ->all();
    $paymentStatus = false;
@endphp
<div>
    <h4 class="text-danger"><i class="bi bi-info-circle-fill"></i> Infos de dettes</h4>
    <hr>
    <div class="row">
        <div class="form-group mb-3">
            <label for="categoryFee" class="form-label">Catégorie de frais</label>
            <x-widget.data.list-cat-scolar-fee id="categoryFee" wire:model.live='category_fee_id' />
        </div>
        @if ($selectedCategoryFee)
            <div class="col-md-6">
                <div class="card info-card">
                    <div class="card-header bg-danger text-uppercase">
                        <h5>{{ $selectedCategoryFee->name }}</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($months as $month)
                                @php
                                    $paymentStatus = $registration->getStatusPayment(
                                        $registration->id,
                                        $selectedCategoryFee->id,
                                        $month['number'],
                                    );
                                @endphp
                                @if ($paymentStatus == false)
                                    <li>{{ $month['name'] }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
