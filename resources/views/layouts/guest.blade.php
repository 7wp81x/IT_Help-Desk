<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IT Helpdesk Ticketing System - @yield('title')</title>
    
    <!-- YOUR LOGO ICON - ADD THIS LINE -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Lottie Player -->
    <script src="https://cdn.jsdelivr.net/npm/@lottiefiles/lottie-player@2.0.0/dist/lottie-player.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #2563EB;
            --primary-dark: #1E40AF;
            --primary-light: #3B82F6;
        }
        
        /* Light Mode */
        [data-theme="light"] {
            --bg-card: #ffffff;
            --bg-input: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --input-border: #cbd5e1;
            --back-home: #94a3b8;
            --back-home-hover: #1e293b;
            --shadow: 0 20px 35px -12px rgba(0,0,0,0.1);
        }
        
        /* Dark Mode */
        [data-theme="dark"] {
            --bg-card: #1a1d24;
            --bg-input: #0f1117;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --border-color: #2d3748;
            --input-border: #2d3748;
            --back-home: #94a3b8;
            --back-home-hover: #ffffff;
            --shadow: 0 20px 35px -12px rgba(0,0,0,0.5);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0a0c10 0%, #0f1722 100%);
            min-height: 100vh;
            overflow-y: auto; /* CHANGED: Allow scrolling */
            overflow-x: hidden;
            margin: 0;
            padding: 20px 0; /* Added padding */
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        /* CARD - exactly 50/50 halves */
        .auth-card {
            background: var(--bg-card);
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            width: 860px;
            max-width: 95vw; /* Changed from 90vw to 95vw for better mobile fit */
            display: flex;
            flex-direction: row;
            position: relative;
            margin: auto;
        }
        
        /* LEFT SIDE - EXACTLY HALF (50%) */
        .auth-image {
            width: 50%;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 0.5rem;
        }
        
        lottie-player {
            width: 100%;
            max-width: 280px; /* Reduced from 320px for better mobile fit */
            height: auto;
            background-color: transparent !important;
        }
        
        lottie-player::part(container) {
            background-color: transparent !important;
        }
        
        lottie-player svg {
            background-color: transparent !important;
        }
        
        /* RIGHT SIDE - EXACTLY HALF (50%) */
        .auth-form {
            width: 50%;
            background: var(--bg-card);
            padding: 1.5rem;
            transition: all 0.3s ease;
            max-height: 90vh;
            overflow-y: auto; /* Allow form content to scroll if needed */
        }
        
        /* Theme Toggle Button */
        .theme-toggle-btn {
            position: absolute;
            top: 0.85rem;
            right: 0.85rem;
            background: rgba(37, 99, 235, 0.12);
            border: 1px solid var(--border-color);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s;
            color: var(--text-primary);
            z-index: 10;
            backdrop-filter: blur(4px);
        }
        
        .theme-toggle-btn:hover {
            background: rgba(37, 99, 235, 0.3);
            transform: scale(1.05);
        }
        
        /* Form Elements */
        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.35rem;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }
        
        .form-control {
            background: var(--bg-input);
            border: 1.5px solid var(--input-border);
            color: var(--text-primary);
            padding: 0.55rem 0.9rem;
            border-radius: 10px;
            transition: all 0.2s;
            font-size: 0.8rem;
        }
        
        .form-control:focus {
            background: var(--bg-input);
            border-color: var(--primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            outline: none;
        }
        
        .form-control::placeholder {
            color: var(--text-secondary);
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        /* Password wrapper for eye icon */
        .password-wrapper {
            position: relative;
        }
        
        .password-wrapper .form-control {
            padding-right: 2.8rem;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            font-size: 1rem;
            transition: color 0.2s;
            z-index: 5;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .form-check-label {
            color: var(--text-primary);
            font-size: 0.75rem;
            cursor: pointer;
        }
        
        .form-check-input {
            background-color: var(--bg-input);
            border-color: var(--input-border);
            width: 0.9rem;
            height: 0.9rem;
            margin-top: 0.1rem;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.65rem;
            margin-top: 0.2rem;
        }
        
        a {
            color: var(--primary-light);
            transition: all 0.2s;
            font-size: 0.75rem;
            text-decoration: none;
            font-weight: 500;
        }
        
        a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 10px;
            margin-bottom: 0.9rem;
            padding: 0.5rem 0.85rem;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }
        
        /* Social Login Buttons */
        .btn-floating {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-input);
            color: var(--text-primary);
            transition: all 0.25s;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        
        .btn-floating:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            transform: translateY(-2px);
            border-color: transparent;
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1rem 0;
        }
        
        .divider hr {
            flex: 1;
            border: none;
            height: 1px;
            background: var(--border-color);
        }
        
        .divider p {
            font-size: 0.7rem;
            margin: 0 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
            border-radius: 40px;
            transition: all 0.25s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
            color: white;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Header styling */
        .auth-header {
            margin-bottom: 1.2rem;
        }
        
        .auth-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .auth-header p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 0;
        }
        
        /* Remember & Forgot row - PERFECT ALIGNMENT */
        .remember-forgot-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        /* Password requirements */
        .req-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            transition: all 0.2s ease;
            font-size: 0.6rem;
        }
        
        .req-badge.valid {
            color: #10b981 !important;
        }
        
        .req-badge.valid i {
            color: #10b981;
        }
        
        .password-reqs-line {
            animation: slideDown 0.2s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* RESPONSIVE: Mobile view */
        @media (max-width: 768px) {
            body {
                padding: 10px 0;
            }
            
            .auth-card {
                flex-direction: column;
                width: 95%;
                max-width: 95%;
                border-radius: 1rem;
            }
            
            .auth-image {
                width: 100%;
                padding: 1rem;
            }
            
            .auth-form {
                width: 100%;
                padding: 1.2rem;
                max-height: none;
                overflow-y: visible;
            }
            
            lottie-player {
                max-width: 180px;
            }
            
            .theme-toggle-btn {
                top: 0.5rem;
                right: 0.5rem;
                width: 32px;
                height: 32px;
            }
            
            .btn-primary {
                width: 100%;
            }
            
            .remember-forgot-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 480px) {
            .auth-form {
                padding: 1rem;
            }
            
            .auth-header h3 {
                font-size: 1.2rem;
            }
            
            .form-control {
                font-size: 0.75rem;
                padding: 0.45rem 0.7rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-container fade-in">
        <div class="auth-card">
            <!-- Theme Toggle Button -->
            <button class="theme-toggle-btn" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
            
            <!-- LEFT SIDE: LOTTIE ANIMATION -->
            <div class="auth-image">
                <lottie-player 
                    src="{{ asset('helpdesk.json') }}"
                    background="transparent"
                    speed="1"
                    loop
                    autoplay>
                </lottie-player>
            </div>

            <!-- RIGHT SIDE: DYNAMIC FORM CONTENT -->
            <div class="auth-form">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Theme Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            
            const savedTheme = localStorage.getItem('card_theme') || 'dark';
            htmlElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                htmlElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('card_theme', newTheme);
                updateThemeIcon(newTheme);
            });
            
            function updateThemeIcon(theme) {
                const icon = themeToggle.querySelector('i');
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>