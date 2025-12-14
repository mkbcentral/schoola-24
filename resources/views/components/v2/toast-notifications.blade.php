@script
<script>
    // Helper function to show Toast notification
    function showToast(icon, title) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            theme: 'auto',
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: icon,
            title: title
        });
    }

    // Listen for success and error messages
    Livewire.on('success-message', (event) => {
        const message = event.message || 'Opération réussie !';
        showToast('success', message);
    });

    Livewire.on('error-message', (event) => {
        const message = event.message || 'Une erreur est survenue';
        showToast('error', message);
    });

    Livewire.on('warning-message', (event) => {
        const message = event.message || 'Attention';
        showToast('warning', message);
    });
</script>
@endscript
