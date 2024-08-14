<div>
    <x-navigation.bread-crumb icon='bi bi-wallet2' label="Gestionnaire categorie frais scolaire">
        <x-navigation.bread-crumb-item label='Categorie frais scolaire' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="tab-container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach ($lisCategoryFee as $categoryFee)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $selectedIndex == $categoryFee->id ? 'active' : '' }}"
                            wire:click='changeIndex({{ $categoryFee->id }})' id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="true"><i class="bi bi-folder-fill"></i>
                            {{ $categoryFee->name }}</button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <livewire:application.fee.scolar.list.list-scolar-fee-page :idCategoryFee='$selectedIndex' />
                </div>
            </div>
        </div>
    </x-content.main-content-page>
    <livewire:application.fee.scolar.form.form-scolar-fee-page :categoryFeeSelected="$categoryFeeSelected">
</div>
