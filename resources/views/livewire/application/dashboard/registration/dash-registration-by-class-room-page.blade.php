<div class="">
    <h4 class="mt-2 text-secondary text-end">Efféctis par option</h4>
    <div class="">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <x-form.label value="{{ __('Choisir option') }}" class="me-2 fw-bold" />
                <x-widget.data.list-option wire:model.live='option_filer' />
            </div>
            <div>
                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical" class="btn-secondary btn-sm">
                    <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                        href="{{ route('class.room.by.option') }}" target='_blank' />
                </x-others.dropdown>
            </div>
        </div>
    </div>
    <div>
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
        <h4>Total/Option: {{ $counter <= 1 ? $counter . ' Elève' : $counter . ' Elèves' }}</h4>
    </div>
</div>
