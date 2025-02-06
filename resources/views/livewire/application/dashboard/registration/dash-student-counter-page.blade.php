<div>
    <div class="card">
        <div class="card-header">
            Effectif par section
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($sections as $section => $count)
                    <div class="col-sm-4">
                        <x-widget.student-counter-widget label="{{ $section }}" value="{{ $count }}">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>Voir
                                    Details</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Imprimer
                                    Liste</a></li>
                        </x-widget.student-counter-widget>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Effectif par option
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($options as $option => $co)
                    <div class="col-sm-4 mb-2">
                        <x-widget.student-counter-widget label="{{ $option }}" value="{{ $co }}">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>
                                    Voir
                                    Details</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Imprimer
                                    Liste</a></li>
                        </x-widget.student-counter-widget>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
