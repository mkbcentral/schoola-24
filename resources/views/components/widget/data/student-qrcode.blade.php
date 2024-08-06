<div wire:ignore.self>
    <div id="qrcode"></div>
</div>
@push('js')
    <script>
        window.addEventListener('loading-qrcode', event => {
            const qrcodeContainer = document.getElementById('qrcode');
            const saveQRCodeButton = document.getElementById('saveQRCode');
            // Generate QR code
            const qr = qrcode(0, 'M');
            const userInfo = event.detail[0]
            qr.addData(JSON.stringify(userInfo));
            qr.make();
            // Create image element
            const img = document.createElement('img');
            img.src = qr.createDataURL();
            img.alt = 'QR Code for student';
            img.id = 'qrCodeImage';
            // Append to container
            qrcodeContainer.appendChild(img);
        });
    </script>
@endpush
