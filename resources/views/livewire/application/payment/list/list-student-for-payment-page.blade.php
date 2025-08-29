<div>
    <div class="card">
        <div class="card-body">
            <livewire:application.payment.form.form-payment-page />

            <div class="mb-3">
                <label for="registration_id" class="form-label fw-bold">
                    Recherche d'élève <span class="text-muted">(Barre de recherche)</span>
                </label>
                <x-autocomplete.list-registration-student-form-school-year model="registration_id" :error="$errors->first('registration_id')"
                    name="registration_id" class="form-control" id="registration_id" />
                @if ($errors->has('registration_id'))
                    <div class="text-danger mt-1">{{ $errors->first('registration_id') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
