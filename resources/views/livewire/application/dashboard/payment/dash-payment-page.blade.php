<div wire:poll.15s>
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mt-2 text-secondary">Recettes {{ $is_by_date == true ? 'par jour' : 'Par mois' }}</h4>

    </div>
    <div>
        <div class="row row-cols-2 row-cols-lg-2 g-2 gy-0  g-lg-2 mb-0">
            @foreach ($categoryFees as $categoryFee)
                <div class="col">
                    <a href="#">
                        <div class="card card-link bg-app mb-0">
                            <div class="card-body ">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="text-uppercase">{{ $categoryFee->name }}</h5>
                                        <h4 class="">
                                            {{ $is_by_date == true
                                                ? app_format_number($categoryFee->getAmountByDate($date_filter), 1)
                                                : app_format_number($categoryFee->getAmountByMonth($month_filter), 1) }}
                                            {{ $categoryFee->currency }}
                                        </h4>
                                    </div>
                                    <i class="bi bi-currency-exchange h4"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <livewire:application.chart.payment.main-payment-chart-page :date="$date_filter" />
    </div>


</div>
