<div>
    <div class="row">
        <div class="col-md-3">
            <x-widget.card-info label="Total élèves" icon="bi-people" :value="$count_all" link="student.list"
                linkLabel="Voir détail" bg="bg-primary" />
        </div>
        @foreach ($registrations as $registration)
            <div class="col-md-3">
                <x-widget.card-info label="Recettes minerval" icon="bi-person-workspace" :value="$registration->total"
                    link="student.list" linkLabel="Voir détail"
                    bg="{{ $registration->is_old ? 'bg-success' : 'bg-danger' }}" />
            </div>
        @endforeach
        <div class="col-md-3">
            <a href="#" class="text-decoration-none">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase">Total par genre</h6>
                                <h3 class="mb-0">M: {{ $genders['male'] }} | F: {{ $genders['female'] }}</h3>
                            </div>
                            <i class="bi bi-person display-4"></i>
                        </div>
                        <small class="d-block mt-2">
                            <i class="bi bi-arrow-right"></i>Voire détails
                        </small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
