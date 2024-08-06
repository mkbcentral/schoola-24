import toastr from 'toastr';
window.$(document).ready(function () {
    toastr.options = {
        "positionClass": "toast-top-right",
        "progressBar": true
    };
    window.addEventListener('added', function (event) {
        toastr.success(event.detail[0].message, 'Validation');
    });
    window.addEventListener('updated', function (event) {
        toastr.info(event.detail[0].message, 'Validation');
    });
    window.addEventListener('error', function (event) {
        toastr.error(event.detail[0].message, 'Alert !');
    });
});

