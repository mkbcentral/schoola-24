<div>
    @props(['disabled' => false, 'error' => ''])
    <div class="password-field">
        <input type="password" id="passwordInput" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}
            class="form-control @error($error) is-invalid @enderror">
        <button type="button" class="password-toggle" id="passwordToggle">
            <i id="iconToggle" class="bi bi-eye"></i>
        </button>
    </div>
</div>
@push('js')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('passwordInput');
            const passwordToggle = document.getElementById('passwordToggle');
            const toggleIcon = document.getElementById('iconToggle');
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
                console.log('OK');
            });
        });
    </script>
@endpush
