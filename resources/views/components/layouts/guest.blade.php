<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Authentification</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">

    {{-- Script inline pour forcer le mode clair sur la page de login --}}
    <script>
        (function() {
            // Forcer le mode clair pour la page de login
            document.documentElement.classList.remove('dark');
            localStorage.setItem('schoola-theme', 'light');
        })();
    </script>

    <!-- Bootstrap 4.6.2 CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap-4.6.2-dist/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    @if (config('app.env') === 'production')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @endif

    <!-- Moment.js -->
    <script src="{{ asset('moment/moment.min.js') }}"></script>

    @vite(['resources/js/app.js'])
    @stack('style')

    <!-- Custom Auth Styles -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --card-shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .auth-container {
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            animation: slideIn 0.6s ease-out;
            overflow: hidden;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .form-control {
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            padding: 11px 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            background: linear-gradient(135deg, #fafbfc 0%, #f8fafc 100%);
            height: 46px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            font-weight: 500;
        }

        .form-control:hover {
            background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
            border-color: #cbd5e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow:
                0 0 0 4px rgba(79, 70, 229, 0.08),
                0 4px 12px rgba(79, 70, 229, 0.15),
                0 1px 3px rgba(0, 0, 0, 0.05);
            outline: none;
            background: white;
            transform: translateY(-2px);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-control.is-invalid:focus {
            box-shadow:
                0 0 0 4px rgba(239, 68, 68, 0.15),
                0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 13px;
            font-weight: 400;
            letter-spacing: 0.2px;
        }

        .input-group {
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: stretch;
        }

        .input-group-text {
            background: linear-gradient(135deg, #fafbfc 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 14px 0 0 14px;
            padding: 0 14px;
            color: #64748b;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            min-width: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 46px;
        }

        .input-group-text i {
            font-size: 16px;
            transition: transform 0.3s ease;
            line-height: 1;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 14px 14px 0;
            padding-left: 12px;
            height: 46px;
            margin: 0;
        }

        .input-group:hover .input-group-text {
            background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
            border-color: #cbd5e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        .input-group:hover .form-control {
            transform: translateY(-1px);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: white;
            box-shadow:
                0 4px 12px rgba(79, 70, 229, 0.15),
                0 1px 3px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .input-group:focus-within .input-group-text i {
            transform: scale(1.1);
        }

        .input-group:focus-within .form-control {
            transform: translateY(-2px);
        }

        /* Animation d'apparition */
        @keyframes inputFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control, .input-group {
            animation: inputFadeIn 0.5s ease-out;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 44px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-item {
            transition: all 0.3s ease;
            padding: 12px;
            border-radius: 12px;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(8px);
        }

        .info-section {
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .trust-badge {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .trust-badge:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #cbd5e1, transparent);
        }

        .divider span {
            position: relative;
            background: white;
            padding: 0 12px;
            color: #9ca3af;
            font-size: 13px;
            font-weight: 500;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .form-check-input:hover {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
            transition: color 0.3s ease;
        }

        .form-check:hover .form-check-label {
            color: var(--primary-color);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        a {
            transition: all 0.3s ease;
        }

        a:hover {
            color: var(--primary-hover) !important;
        }

        .spinner-border {
            width: 1rem;
            height: 1rem;
            vertical-align: middle;
        }

        @media (max-width: 991.98px) {
            .auth-card {
                margin: 20px;
            }
        }

        /* Logo animation */
        .logo-icon {
            display: inline-block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>

<body>
    <div class="auth-container">
           {{ $slot }}
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="{{ asset('bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js') }}"></script>

    @stack('js')
</body>

</html>
