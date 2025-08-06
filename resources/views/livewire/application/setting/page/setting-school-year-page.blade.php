<div>
    <div class="p-5 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);">
        <h3 class="mb-4 fw-bold text-primary-emphasis d-flex align-items-center" style="letter-spacing: 1px;">
            <i class="bi bi-calendar3 me-2"></i>
            Paramètres année scolaire
        </h3>
        <div class="d-flex flex-column gap-2">
            @foreach ($schoolYears as $schoolYear)
                <div class="form-check d-flex align-items-center p-3 rounded-3 shadow-sm
                    @if ($schoolYear->id == $schoolYearId) border border-3 border-primary bg-primary-subtle @endif"
                    style="background: rgba(255,255,255,0.85); transition: box-shadow 0.2s;">
                    <input class="form-check-input me-3 custom-radio" wire:model.live='schoolYearId' type="radio"
                        value="{{ $schoolYear->id }}" id="{{ $schoolYear->name }}">
                    <label class="form-check-label flex-grow-1" for="{{ $schoolYear->name }}"
                        style="font-size: 1.25em; font-weight: 600; color: #374151;">
                        {{ $schoolYear->name }}
                        @if ($schoolYear->id == $schoolYearId)
                            <span class="badge bg-primary ms-2">Active</span>
                        @endif
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <style>
        /* Custom radio style */
        .custom-radio[type="radio"] {
            appearance: none;
            width: 1.5em;
            height: 1.5em;
            border: 2px solid #6366f1;
            border-radius: 50%;
            background: #fff;
            transition: box-shadow 0.2s, border-color 0.2s;
            position: relative;
            margin-top: 0;
        }

        .custom-radio[type="radio"]:checked {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px #6366f133;
            background: #6366f1;
        }

        .custom-radio[type="radio"]:checked::after {
            content: '';
            display: block;
            width: 0.7em;
            height: 0.7em;
            border-radius: 50%;
            background: #fff;
            position: absolute;
            top: 0.4em;
            left: 0.4em;
        }
    </style>
</div>
