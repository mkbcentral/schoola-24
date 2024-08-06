<div class="mt-2">
    <div class="d-flex">
        <div class="d-flex align-items-center">
            <x-form.label value="{{ __('Choisir option') }}" class="me-2 fw-bold" />
            <x-widget.data.list-option wire:model.live='option_filer' />
        </div>
    </div>
    <div class="row row-cols-2 row-cols-lg-3 g-2 gy-0  g-lg-2 mb-0 mt-2">
        @foreach ($classRooms as $classRoom)
            <div class="col">
                <a href="{{ route('registration.by.class-room', $classRoom->id) }}">
                    <div class="card card-link text-bg-info">
                        <div class="card-body">
                            <h5 class="card-title">{{ $classRoom->getOriginalClassRoomName() }}
                                <span
                                    class="fw-bold">({{ $classRoom->getRegistrationCountForCurrentSchoolYear($month_filter) }})</span>
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
