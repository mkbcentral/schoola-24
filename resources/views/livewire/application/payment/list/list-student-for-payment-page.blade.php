<div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <livewire:application.payment.form.form-payment-page />
            <div class="">
                <x-autocomplete.list-registration-student-form-school-year model="registration_id" :error="$errors->first('registration_id')"
                    name="registration_id" id="registration_id" />

                @if ($errors->has('registration_id'))
                    <div class="alert alert-danger mt-3 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>{{ $errors->first('registration_id') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
