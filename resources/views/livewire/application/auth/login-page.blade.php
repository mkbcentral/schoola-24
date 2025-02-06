<form id="loginForm" wire:submit='login'>
    <div class="mb-2">
        <x-form.auth-form-field type='email' wire:model='form.email' :error="'form.email'" placeholder="Adresse email" />
        <x-errors.validation-error value='form.email' />
    </div>

    <div class="mb-3 mt-4">
        <x-form.auth-form-password wire:model='form.password' :error="'form.password'" placeholder="Mot de passe" />
        <x-errors.validation-error value='form.password' />
    </div>

    <div class="d-flex justify-content-between mt-4 mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe">
            <label class="form-check-label" for="rememberMe">Se souvernir de moi</label>
        </div>
        <a href="#" class="text-primary" style="font-size: 0.8rem;">Mot de pass oublié?</a>
    </div>
    <button type="submit" class="btn btn-primary btn-login">
        <x-widget.loading-circular-md size="sm" wire:loading wire:target='login' />
        <span wire:loading.class="d-none"> Se Connecter</span>

    </button>

</form>
