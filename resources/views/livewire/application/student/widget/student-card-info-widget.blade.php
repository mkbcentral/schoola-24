<div>
    @push('style')
        <style>
            .user-card {
                max-width: 540px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .user-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            .user-avatar {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 50%;
                border: 3px solid #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            #qrcode {
                text-align: center;
                margin-top: 15px;
                padding: 10px;
                background-color: #ffffff;
                border-radius: 0 0 5px 5px;
            }

            #qrcode img {
                display: inline-block;
                max-width: 100%;
                height: auto;
            }
        </style>
    @endpush
    <div class="card user-card">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Nom:</strong> {{ $registration?->student->name }}
                </li>
                <li class="list-group-item">
                    <strong>Genre:</strong> {{ $registration->student->gender }}
                </li>
                <li class="list-group-item">
                    <strong>Age:</strong>
                    {{ $registration->student->getFormattedAg() }}
                </li>
                <li class="list-group-item">
                    <strong>Classe:</strong>
                    {{ $registration->classRoom->getOriginalClassRoomName() }}
                </li>
                <li class="list-group-item">
                    <strong>Responsable:</strong>
                    {{ $registration->student->responsibleStudent->name }}
                </li>
                <li class="list-group-item">
                    <strong>Contact:</strong>
                    {{ $registration->student->responsibleStudent->phone . ' - ' . $registration->student->responsibleStudent->other_phone }}
                </li>
            </ul>

        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                @can('manage-student')
                    <x-form.app-button wire:click='edit({{ $registration->student }})' data-bs-toggle="modal"
                        data-bs-target="#form-edit-student" textButton='Modifier' icon="bi bi-pencil-fill"
                        class="btn-primary" />
                @endcan
            </div>
        </div>
        <div class="text-center">
            <img src="{{ asset($registration->qr_code) }}" width="120">
        </div>
        <div class="text-center mb-4 mt-4">
            <a target="_blank" href="{{ route('print.student.payemnts', $registration) }}" class="btn btn-primary"><i
                    class="bi bi-printer-fill"></i> Imprimer
                détail</a>
        </div>
    </div>

    <livewire:application.student.form.form-edit-student-page />
</div>
