<div>
    <div class="row">
        <div class="col-md-3">
            <x-widget.card-info label="Total élèves" icon="bi-people" :value="$count_all" link="student.list"
                linkLabel="Voir détail" iconColor="primary" />
        </div>
        @foreach ($registrations as $registration)
            <div class="col-md-3">
                <x-widget.card-info label="{{ $registration->is_old ? 'Nouveau' : 'Ancien' }}" icon="bi-person-workspace"
                    :value="$registration->total" link="student.list" linkLabel="Voir détail"
                    iconColor="{{ $registration->is_old ? 'success' : 'danger' }}" />
            </div>
        @endforeach
        <div class="col-md-3">
            <x-widget.card-info label="Total par genre" icon="bi-people" :value="'M: ' . $genders['male'] . ' | F: ' . $genders['female']" link="student.list"
                linkLabel="Voir détail" iconColor="primary" />

        </div>
    </div>
</div>
