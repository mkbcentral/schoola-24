<div class="">
    <div class="row mt-2">
        <x-others.card-dashboard title='{{ $counter_new }}' subtitle='Inscription' color='text-bg-primary'
            icon='bi bi-person-fill-add'
            href="{{ route(
                $is_by_date == true ? 'registration.date' : 'registration.month',
                $is_by_date == true ? [0, $date_filter] : [0, $month_filter],
            ) }}" />
        <x-others.card-dashboard title='{{ $counter_old }}' subtitle='Réinscription' color='text-bg-danger'
            icon='bi bi-person-fill-up'
            href="{{ route(
                $is_by_date == true ? 'registration.date' : 'registration.month',
                $is_by_date == true ? [1, $date_filter] : [1, $month_filter],
            ) }}" />

    </div>
</div>
