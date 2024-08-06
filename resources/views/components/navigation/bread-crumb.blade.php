@props(['color' => '', 'icon' => '', 'label' => ''])
<div class="app-content-header"> <!--begin::Container-->
    <div class=""> <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0 {{ $color }}"><i class="{{ $icon }}"></i> {{ $label }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    {{ $slot }}

                </ol>
            </div>
        </div> <!--end::Row-->
    </div> <!--end::Container-->
</div>
