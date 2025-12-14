<div>
    {{-- Offcanvas Inscription Nouvel Élève --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="registerNewStudentOffcanvas"
        aria-labelledby="registerNewStudentLabel" wire:ignore.self style="width: 700px;">
        <div class="offcanvas-header text-white">
            <h5 class="offcanvas-title" id="registerNewStudentLabel">
                <i class="bi bi-person-plus"></i> Inscrire un nouvel élève
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            {{-- Progress Steps --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="flex-fill">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $form->currentStep === 1 ? '50%' : '100%' }}"
                                aria-valuenow="{{ $form->currentStep }}" aria-valuemin="1" aria-valuemax="2"></div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-center" style="width: 50%;">
                        <div class="badge {{ $form->currentStep === 1 ? 'bg-success' : 'bg-secondary' }} mb-1">
                            {{ $form->currentStep === 1 ? '1' : '✓' }}
                        </div>
                        <div class="small fw-bold">Informations élève</div>
                    </div>
                    <div class="text-center" style="width: 50%;">
                        <div class="badge {{ $form->currentStep === 2 ? 'bg-success' : 'bg-secondary' }} mb-1">
                            2
                        </div>
                        <div class="small fw-bold">Inscription</div>
                    </div>
                </div>
            </div>

            <form wire:submit="{{ $form->currentStep === 1 ? 'nextStep' : 'register' }}">
                {{-- Étape 1: Informations de l'élève --}}
                @if ($form->currentStep === 1)
                    @include('livewire.application.v2.registration.form.partials.step-one-student-info', [
                        'form' => $form,
                        'responsibleStudents' => $responsibleStudents,
                    ])
                @endif

                {{-- Étape 2: Informations d'inscription --}}
                @if ($form->currentStep === 2)
                    @include(
                        'livewire.application.v2.registration.form.partials.step-two-registration-info',
                        [
                            'form' => $form,
                            'sections' => $sections,
                            'options' => $options,
                            'classRooms' => $classRooms,
                            'createdAt' => $createdAt,
                        ]
                    )
                @endif
            </form>
        </div>
    </div>
    @script
        <script>
            $wire.on('registration-created', () => {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById(
                    'registerNewStudentOffcanvas'));
                if (offcanvas) {
                    offcanvas.hide();
                }
            });
        </script>
    @endscript
</div>
