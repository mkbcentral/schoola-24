<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Paramètres année scolaire</h5>
            @foreach ($schoolYears as $schoolYear)
                <div class="form-check mb-3">
                    <input class="form-check-input" wire:model.live='schoolYearId' type="radio"
                        value="{{ $schoolYear->id }}" id="{{ $schoolYear->name }}">
                    <label class="form-check-label" for="{{ $schoolYear->name }}"
                        style="font-size: 1.2em; font-weight: bold; color: #333;">
                        {{ $schoolYear->name }}
                    </label>
                </div>
            @endforeach
        </div>²
    </div>
</div>
