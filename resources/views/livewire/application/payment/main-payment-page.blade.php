<div>
    <x-navigation.bread-crumb icon='bi bi-currency-exchange' label="Gestionnaire des payments">
        <x-navigation.bread-crumb-item label='Paiements des frais' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach ($lisCategoryFee as $categoryFee)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $selectedIndex == $categoryFee->id ? 'active' : '' }}"
                            wire:click='changeIndex({{ $categoryFee->id }})' id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="true"><i class="bi bi-folder"></i>
                            {{ $categoryFee->name }}</button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @if ($categoryFeeSelected->is_paid_in_installment)
                        <livewire:application.payment.list.list-report-payment-by-tranch-page :categoryFeeId='$selectedIndex' />
                    @else
                        <livewire:application.payment.list.list-report-payment-page :categoryFeeId='$selectedIndex' />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
