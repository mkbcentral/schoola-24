<div>
    @push('style')
        <style>
            .card {
                width: 350px;
                height: 200px;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                position: relative;
                background-color: white;
            }

            .blue-section {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 60%;
                background-color: #000080;
                border-top-left-radius: 15px;
                border-bottom-left-radius: 15px;
                border-top-right-radius: 100px;
                border-bottom-right-radius: 100px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .logo {
                width: 80px;
                height: 80px;
                background-color: white;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 10px;
            }

            .logo img {
                width: 60px;
                height: auto;
            }

            .school-name {
                color: white;
                font-size: 1.4rem;
                font-weight: bold;
                text-align: center;
            }

            .schoola-id {
                position: absolute;
                right: 20px;
                top: 20px;
                font-size: 1rem;
                font-weight: bold;
            }

            .qr-code {
                position: absolute;
                right: 20px;
                top: 50px;
                width: 80px;
                height: 80px;
            }

            .id-number {
                position: absolute;
                right: 20px;
                bottom: 40px;
                font-size: 0.9rem;
                font-weight: bold;
                color: #000080;
            }

            .website {
                position: absolute;
                right: 20px;
                bottom: 20px;
                color: #666;
                font-size: 0.7rem;
            }
        </style>
    @endpush
    <div class="card">
        <div class="blue-section">
            <div class="logo">
                <img src="{{ public_path('images/logo.png') }}" alt="Open book icon">
            </div>
            <div class="school-name">C.S. AQUILA</div>
        </div>
        <div class="schoola-id">SCHOOLA-ID</div>
        <img src="/qr-code" alt="QR Code" class="qr-code">
        <div class="id-number">ID:001-AQ</div>
        <div class="website">www.schoola.app</div>
    </div>
</div>
