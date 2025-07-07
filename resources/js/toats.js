import toastr from 'toastr';

$(function () {
    toastr.options = {
        positionClass: "toast-top-right",
        progressBar: true,
        closeButton: true,
        timeOut: 4000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
        showDuration: 300,
        hideDuration: 300,
        toastClass: 'custom-toastr'
    };

    const toastMap = {
        added: { method: 'success', title: 'Validation' },
        updated: { method: 'info', title: 'Validation' },
        error: { method: 'error', title: 'Alert !' }
    };

    Object.keys(toastMap).forEach(eventType => {
        window.addEventListener(eventType, function (event) {
            const detail = event.detail && event.detail[0];
            if (detail && detail.message) {
                toastr[toastMap[eventType].method](detail.message, toastMap[eventType].title);
            }
        });
    });
});
