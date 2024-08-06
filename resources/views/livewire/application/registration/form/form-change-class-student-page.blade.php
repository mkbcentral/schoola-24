<div>
    <x-modal.build-modal-fixed idModal='form-change-class-student' size='md'
        headerLabel="BASCULER VERS UNE AUTRE CLASSE" headerLabelIcon="bi bi-arrow-left-right">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        @if ($registration)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="fw-bold">Nom:</span>
                        {{ $registration->student->name }}
                    </h5>
                    <h5 class="card-title">
                        <span class="fw-bold">Ancienne classe:</span>
                        {{ $registration->classRoom->getOriginalClassRoomName() }}
                    </h5>
                </div>
            </div>
            <form wire:submit='changeStudentClass'>
                <div class="form-group">
                    <x-form.label value="{{ __('Nouvelle classe') }}" class="fw-bold mt-2" />
                    <select id="my-select" class="form-control" wire:model='class_room_id' name="class_room_id">
                        <option value="">Choisir...</option>
                        @foreach ($listClassRoom as $classRoom)
                            <option value="{{ $classRoom->id }}">{{ $classRoom->getOriginalClassRoomName() }}</option>
                        @endforeach
                    </select>
                    <x-errors.validation-error value='class_room_id' />
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <x-form.app-button type='submit' textButton="Marquer abandon" icon="bi bi-arrow-left-right"
                        class="app-btn" />
                </div>
            </form>
        @endif


    </x-modal.build-modal-fixed>
</div>
