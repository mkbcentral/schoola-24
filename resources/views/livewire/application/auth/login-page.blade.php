<div class="login-box">
    <div class="card">
        <div class="card-header text-center">
            <img class="" src="{{ asset('images/logo.svg') }}" alt="Logo" width="">
        </div>
        <div class="card-body">
            <h4 class="text-app text-uppercase text-center text-bold align-items-center">
                <i class="bi bi-person-workspace"></i> se connecter
            </h4>
            <form wire:submit='login' class="mt-4">
                <div>
                    <x-form.label value="{{ __('Adresse email') }}" class="text-secondary" />
                    <x-form.input type='email' wire:model='form.email' :error="'form.email'" style="height: 45px" />
                    <x-errors.validation-error value='form.email' />
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Mot de passe') }}" class="text-secondary" />
                    <x-form.input type='password' wire:model='form.password' :error="'form.password'" style="height: 45px" />
                    <x-errors.validation-error value='form.password' />
                </div>
                <div class="mt-4">
                    <x-form.button class="app-btn w-100" type='submit'>
                        <x-widget.loading-circular-md wire:loading wire:target='login' />
                        <span wire:loading.class='d-none'>Se connecter</span>
                    </x-form.button>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
