<div class="">
    <h4 class="mt-2 text-secondary text-end">Efféctis par section</h4>
    <div>
        <div class="row row-cols-2 row-cols-lg-3 g-2 gy-0  g-lg-2 mb-0 mt-2">
            @foreach ($sections as $section)
                <div class="col">
                    <a href="#">
                        <div class="card card-link text-bg-success">
                            <div class="card-body">
                                <h5 class="card-title">{{ $section->name }}
                                    <span
                                        class="fw-bold">({{ $section->getRegistrationCountForCurrentSchoolYear() }})</span>
                                </h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
