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
<div>
    <h4><i class="bi bi-info-circle-fill"></i> Infos de payments</h4>
    <hr>
    <div class="row">
        @foreach ($months as $month)
            @php
                $payments = $registration
                    ->payments()
                    ->where('month', $month['number'])
                    ->where('is_paid', true)
                    ->get();
            @endphp
            @if (!$payments->isEmpty())
                <div class="col-md-4 ">
                    <div class="card info-card">
                        <div class="card-header bg-info text-uppercase">
                            <h5>{{ $month['name'] }}</h5>
                        </div>
                        <div class="card-body">
                            <ul>
                                @foreach ($registration->payments()->where('month', $month['number'])->where('is_paid', true)->get() as $payment)
                                    <li>{{ $payment->scolarFee->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>
