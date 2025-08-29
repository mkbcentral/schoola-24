document.addEventListener('DOMContentLoaded', function () {
    console.log('modal-grag.js loaded');
    var modalElement = document.getElementById('{{ $idModal }}');
    if (!modalElement) return;
    new bootstrap.Modal(modalElement);

    // Accessibilité : inert quand fermé
    modalElement.addEventListener('hidden.bs.modal', function () {
        modalElement.setAttribute('inert', 'true');
    });

    modalElement.addEventListener('shown.bs.modal', function () {
        modalElement.removeAttribute('inert');
    });

    // Drag & Drop
    const header = modalElement.querySelector('.modal-header');
    const dialog = modalElement.querySelector('.modal-dialog');
    let isDragging = false, startX, startY, origX, origY, origWidth, origHeight;

    header.style.cursor = 'move';

    header.addEventListener('mousedown', function (e) {
        if (e.target.closest('.btn-close')) return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        const rect = dialog.getBoundingClientRect();
        origX = rect.left;
        origY = rect.top;
        origWidth = rect.width;
        origHeight = rect.height;
        dialog.style.width = origWidth + 'px';
        dialog.style.height = origHeight + 'px';
        dialog.style.position = 'fixed';
        dialog.style.margin = '0';
        dialog.style.zIndex = 1056;
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        let dx = e.clientX - startX;
        let dy = e.clientY - startY;
        dialog.style.left = (origX + dx) + 'px';
        dialog.style.top = (origY + dy) + 'px';
        dialog.style.width = origWidth + 'px';
        dialog.style.height = origHeight + 'px';
    });

    document.addEventListener('mouseup', function () {
        if (isDragging) {
            isDragging = false;
            document.body.style.cursor = '';
        }
    });

    // Resize
    const resizeHandle = modalElement.querySelector('.resize-handle');
    let isResizing = false, startWidth, startHeight;

    resizeHandle.addEventListener('mousedown', function (e) {
        e.preventDefault();
        isResizing = true;
        startX = e.clientX;
        startY = e.clientY;
        const rect = dialog.getBoundingClientRect();
        startWidth = rect.width;
        startHeight = rect.height;
        dialog.style.position = 'fixed';
        dialog.style.margin = '0';
        dialog.style.zIndex = 1056;
    });

    document.addEventListener('mousemove', function (e) {
        if (!isResizing) return;
        let dx = e.clientX - startX;
        let dy = e.clientY - startY;
        dialog.style.width = Math.max(300, startWidth + dx) + 'px';
        dialog.style.height = Math.max(200, startHeight + dy) + 'px';
    });

    document.addEventListener('mouseup', function () {
        if (isResizing) {
            isResizing = false;
        }
    });

    // Reset position and size when modal is closed
    modalElement.addEventListener('hidden.bs.modal', function () {
        dialog.style.left = '';
        dialog.style.top = '';
        dialog.style.position = '';
        dialog.style.margin = '';
        dialog.style.zIndex = '';
        dialog.style.width = '';
        dialog.style.height = '';
        document.body.style.cursor = '';
    });

    // Ensure modal is not re-centered by Bootstrap after drag
    modalElement.addEventListener('show.bs.modal', function () {
        dialog.style.left = '';
        dialog.style.top = '';
        dialog.style.position = '';
        dialog.style.margin = '';
        dialog.style.zIndex = '';
        dialog.style.width = '';
        dialog.style.height = '';
    });
});
