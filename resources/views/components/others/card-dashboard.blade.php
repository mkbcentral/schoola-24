@props(['title' => '', 'subtitle' => '', 'color' => '', 'icon' => ''])
<div class="col-md-6"> <!--begin::Small Box Widget 1-->
    <div class="small-box {{ $color }}">
        <div class="inner">
            <h3>{{ $title }}</h3>
            <p>{{ $subtitle }}</p>
        </div>
        <i class="small-box-icon {{ $icon }}"></i>
        <a {{ $attributes }}
            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
            Voir details <i class="bi bi-link-45deg"></i>
        </a>
    </div> <!--end::Small Box Widget 1-->
</div>
