<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f9fafb" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#111827" media="(prefers-color-scheme: dark)">
    <title>{{ config('app.name', 'IT Helpdesk') }} - Ticketing System</title>

    <!-- CRITICAL FIX: Force dark mode colors BEFORE anything loads -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* Immediate base styles before Tailwind loads */
        html { background: #f9fafb; }
        html.dark { background: #111827; }
        body { background: inherit; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        
        /* Immediate dark mode fix - prevents white flash */
        html.dark body {
            background-color: #111827 !important;
            color: #f9fafb !important;
        }
        html.dark .bg-white {
            background-color: #1f2937 !important;
        }
        html.dark .bg-gray-50 {
            background-color: #111827 !important;
        }
        html.dark .text-gray-900 {
            color: #f9fafb !important;
        }
        html.dark .text-gray-700 {
            color: #e5e7eb !important;
        }
        html.dark .text-gray-600 {
            color: #9ca3af !important;
        }
        html.dark .text-gray-500 {
            color: #9ca3af !important;
        }
        html.dark .border-gray-200 {
            border-color: #374151 !important;
        }
        
        /* Modal Styles */
        .auth-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            transition: background 0.3s ease;
        }
        
        .auth-modal.active {
            background: rgba(0, 0, 0, 0.85);
            visibility: visible;
        }
        
        .auth-modal-content {
            position: relative;
            width: 90%;
            max-width: 950px;
            background: transparent;
            border-radius: 28px;
            transform: scale(0);
            transition: transform 0.4s cubic-bezier(0.34, 1.2, 0.64, 1);
            transform-origin: var(--origin-x, center) var(--origin-y, center);
        }
        
        .auth-modal.active .auth-modal-content {
            transform: scale(1);
        }
        
        .modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 10;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .modal-close i {
            color: white;
            font-size: 1.2rem;
        }
        
        .auth-card-popup {
            background: white;
            border-radius: 28px;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            min-width: 0;
            flex-wrap: wrap;
        }
        
        html.dark .auth-card-popup {
            background: #1f2937;
        }
        
        .auth-card-popup .auth-image {
            width: 45%;
            background: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            min-width: 0;
        }
        
        /* Forgot & Confirm forms use orange gradient */
        .auth-card-popup .auth-image.forgot-image {
            background: linear-gradient(135deg, #F59E0B 0%, #EA580C 100%);
        }
        
        .auth-card-popup .auth-form {
            width: 55%;
            padding: 1.8rem;
            background: white;
            max-height: 85vh;
            overflow-y: auto;
            min-width: 0;
        }
        
        html.dark .auth-card-popup .auth-form {
            background: #1f2937;
        }
        
        .auth-form::-webkit-scrollbar {
            width: 5px;
        }
        
        .auth-form::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .auth-form::-webkit-scrollbar-thumb {
            background: #2563EB;
            border-radius: 10px;
        }
        
        html.dark .auth-form::-webkit-scrollbar-track {
            background: #374151;
        }
        
        @media (max-width: 768px) {
            .auth-card-popup {
                flex-direction: column;
            }
            .auth-card-popup .auth-image,
            .auth-card-popup .auth-form {
                width: 100%;
            }
            .auth-card-popup .auth-image {
                padding: 1rem;
                min-height: 180px;
            }
            .auth-card-popup .auth-form {
                max-height: 65vh;
            }
            lottie-player {
                max-width: 160px !important;
                height: auto !important;
            }
        }
        
        .req-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            font-size: 0.55rem;
            transition: all 0.2s ease;
        }
        
        .req-badge.valid {
            color: #10b981 !important;
        }
        
        .strength-bar {
            height: 2px;
            border-radius: 2px;
            margin-top: 0.3rem;
            background: #e5e7eb;
            transition: all 0.2s ease;
        }
        
        html.dark .strength-bar {
            background: #374151;
        }
        
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #2563EB, #1E40AF);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        
        .scroll-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-top:hover {
            transform: translateY(-3px);
        }
        
        .scroll-top i {
            color: white;
            font-size: 1.2rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 0.6s linear infinite;
        }
    </style>

    <!-- Immediate dark mode application -->
    <script>
        (function() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            const htmlRoot = document.documentElement;
            
            if (darkMode) {
                htmlRoot.classList.add('dark');
                htmlRoot.style.backgroundColor = '#111827';
            } else {
                htmlRoot.classList.remove('dark');
                htmlRoot.style.backgroundColor = '#f9fafb';
            }
            
            // Ensure body styling is applied immediately once the body is available
            if (document.body) {
                document.body.style.transition = 'none';
            } else {
                window.addEventListener('DOMContentLoaded', function() {
                    document.body.style.transition = 'none';
                });
            }
        })();
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 50%, #ffffff 100%);
        }
        
        html.dark .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px -15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #2563EB;
            box-shadow: 0 20px 30px -15px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

@php
    $modalState = null;
    if (session('showRegisterModal') || request()->routeIs('register')) {
        $modalState = 'register';
    } elseif (session('showLoginModal') || request()->routeIs('login')) {
        $modalState = 'login';
    } elseif (session('showForgotModal') || request()->routeIs('password.request')) {
        $modalState = 'forgot';
    }
@endphp

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-150{{ $modalState ? ' overflow-hidden' : '' }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="
        // Don't transition on init
        document.body.style.transition = 'none';
        
        // Update on change
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            if(val) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Re-enable transitions after initial setup
            setTimeout(() => {
                document.body.style.transition = 'background-color 0.15s, color 0.15s';
            }, 100);
        });
      ">

    <!-- Hero Section -->
    <section class="hero-section min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md fixed w-full z-50 border-b border-gray-200 dark:border-gray-700">
            <div class="container mx-auto px-4 md:px-6 py-4">
                <div class="flex justify-between items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <i class="fas fa-headset text-2xl text-blue-600"></i>
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Helpdesk</span>
                    </a>
                    
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#home" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">Home</a>
                        <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">Features</a>
                        <a href="#stats" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">Stats</a>
                        <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">Contact</a>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                        </button>
                        
                        @guest
                            <a href="#" class="auth-trigger px-4 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white transition" data-auth-type="login">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                            <a href="#" class="auth-trigger px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition shadow-md" data-auth-type="register">
                                <i class="fas fa-user-plus mr-1"></i> Register
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div id="home" class="container mx-auto px-4 md:px-6 pt-32 pb-20">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 text-center md:text-left" data-aos="fade-right">
                    <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-rocket"></i>
                        <span>IT Support Reimagined</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        Streamline Your <span class="text-blue-600">IT Support</span> Operations
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Modern ticketing system to manage, track, and resolve IT incidents faster than ever before.
                    </p>
                  <div class="flex gap-4 justify-center md:justify-start">
                    @guest
                        <a href="#" class="auth-trigger px-6 py-3 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg" data-auth-type="login">
                            Get Started <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="{{ route('agent.apply') }}" class="px-6 py-3 rounded-lg border-2 border-blue-600 text-blue-600 font-semibold hover:bg-blue-600 hover:text-white transition">
                            Apply as Agent
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition">
                            Dashboard <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @endguest
                </div>
                </div>
                <div class="md:w-1/2 mt-10 md:mt-0" data-aos="fade-left">
                    <lottie-player 
                        src="{{ asset('team.json') }}"
                        background="transparent"
                        speed="1"
                        style="width: 100%; max-width: 450px; height: auto; margin: 0 auto;"
                        loop 
                        autoplay>
                    </lottie-player>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-card text-center p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-2xl text-blue-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white" id="totalTickets">0</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Tickets</div>
                </div>
                <div class="stat-card text-center p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-spinner text-2xl text-blue-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white" id="openTickets">0</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Open Tickets</div>
                </div>
                <div class="stat-card text-center p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-2xl text-blue-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white" id="resolvedTickets">0</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Resolved</div>
                </div>
                <div class="stat-card text-center p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white" id="totalUsers">0</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Active Users</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Enterprise-Grade Features</h2>
                <p class="text-gray-600 dark:text-gray-400">Everything you need to run a modern IT helpdesk</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="feature-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-bolt text-xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Lightning Fast</h3>
                    <p class="text-gray-600 dark:text-gray-400">Quick ticket routing and real-time response for critical IT issues.</p>
                </div>
                <div class="feature-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Advanced Analytics</h3>
                    <p class="text-gray-600 dark:text-gray-400">Deep insights into ticket trends and team performance metrics.</p>
                </div>
                <div class="feature-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Security First</h3>
                    <p class="text-gray-600 dark:text-gray-400">Enterprise-grade encryption and role-based access control.</p>
                </div>
                <div class="feature-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-paperclip text-xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">File Management</h3>
                    <p class="text-gray-600 dark:text-gray-400">Attach logs, screenshots, and documents to any ticket.</p>
                </div>
                <div class="feature-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Smart Notifications</h3>
                    <p class="text-gray-600 dark:text-gray-400">Real-time alerts via email and push notifications.</p>
                </div>
                <div class="feature-card bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-globe text-xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">24/7 Access</h3>
                    <p class="text-gray-600 dark:text-gray-400">Fully responsive design works on any device, anywhere.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-gray-900 to-gray-800">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to transform your IT support?</h2>
            <p class="text-gray-300 mb-8">Join 10,000+ companies already using our platform</p>
            @guest
                <a href="#" class="auth-trigger inline-flex items-center px-6 py-3 rounded-lg bg-white text-gray-900 font-semibold hover:bg-gray-100 transition shadow-lg" data-auth-type="register">
                    <i class="fas fa-user-plus mr-2"></i> Start Free Trial
                </a>
            @else
                <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-6 py-3 rounded-lg bg-white text-gray-900 font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-ticket-alt mr-2"></i> Create Your First Ticket
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-gray-400 py-12">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h5 class="text-white text-lg font-bold mb-4"><i class="fas fa-headset mr-2"></i>Helpdesk</h5>
                    <p class="text-sm">Modern IT support ticketing system for forward-thinking organizations.</p>
                    <div class="flex space-x-3 mt-4">
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-twitter text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-linkedin-in text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition"><i class="fab fa-github text-sm"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">Product</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition">API</a></li>
                        <li><a href="#" class="hover:text-white transition">Security</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">Company</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">About</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-bold mb-4">Contact</h5>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fas fa-envelope mr-2"></i> hello@helpdesk.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> San Francisco, CA</li>
                    </ul>
                </div>
            </div>
            <hr class="my-8 border-gray-800">
            <div class="text-center text-sm">
                &copy; {{ date('Y') }} Helpdesk. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scroll Top Button -->
    <div class="scroll-top" id="scrollTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- ========== AUTH MODAL WITH ALL FORMS ========== -->
    <div class="auth-modal{{ $modalState ? ' active' : '' }}" id="authModal">
        <div class="auth-modal-content" id="authModalContent">
            <div class="modal-close" id="closeModal"><i class="fas fa-times"></i></div>
            <div class="auth-card-popup">
                
                <!-- LOGIN FORM -->
                <div id="loginFormContainer" style="display: {{ $modalState === 'login' ? 'flex' : 'none' }}; width: 100%;">
                    <div class="auth-image">
                        <lottie-player src="{{ asset('helpdesk.json') }}" background="transparent" speed="1" style="width: 220px; height: 220px; margin: 0 auto;" loop autoplay></lottie-player>
                    </div>
                    <div class="auth-form">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Welcome Back</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Sign in to continue to your account</p>
                        
                        <form method="POST" action="{{ route('login') }}" id="modalLoginForm">
                            @csrf
                            @if($errors->hasBag('login') && ($errors->login->any() || session('status')))
                                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                                    {{ $errors->login->first('email') ?: $errors->login->first('password') ?: session('status') }}
                                </div>
                            @endif
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10" placeholder="Enter your password" required>
                                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400" onclick="togglePassword(this)">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                                </label>
                                <a href="{{ route('password.request') }}" id="forgotPasswordLink" class="text-sm text-blue-600 hover:text-blue-700">Forgot password?</a>
                            </div>
                            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition">Sign In</button>
                        </form>
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                            Don't have an account? <a href="#" id="switchToRegisterBtn" class="text-blue-600 hover:text-blue-700 font-semibold">Register</a>
                        </p>
                    </div>
                </div>
                
                <!-- REGISTER FORM -->
                <div id="registerFormContainer" style="display: {{ $modalState === 'register' ? 'flex' : 'none' }}; width: 100%;">
                    <div class="auth-image">
                        <lottie-player src="{{ asset('helpdesk.json') }}" background="transparent" speed="1" style="width: 220px; height: 220px; margin: 0 auto;" loop autoplay></lottie-player>
                    </div>
                    <div class="auth-form">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Join our IT Helpdesk System</p>
                        
                        <form method="POST" action="{{ route('register') }}" id="modalRegisterForm">
                            @csrf
                            <div id="modalRegisterError" class="hidden mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm"></div>
                            @if($errors->hasBag('register') && $errors->register->any())
                                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                                    {{ $errors->register->first('name') ?: $errors->register->first('email') ?: $errors->register->first('phone') ?: $errors->register->first('password') ?: $errors->register->first('password_confirmation') ?: $errors->register->first('role') }}
                                </div>
                            @endif
                            <input type="hidden" name="role" value="user">
                            <div class="mb-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 text-sm">
Want to be part of something great? <a href="{{ route('agent.apply') }}" class="font-semibold text-blue-700 hover:text-blue-800">Join our team today</a>.                            </div>
                            <div class="mb-3"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label><input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" placeholder="Enter your full name" required></div>
                            <div class="mb-3"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label><input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" placeholder="Enter your email" required></div>
                            <div class="mb-3"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label><input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" placeholder="09XXXXXXXXX" required></div>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                                <div class="relative">
                                    <input type="password" id="modalPassword" name="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pr-10" placeholder="Create a password" required>
                                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" onclick="togglePassword(this)"><i class="far fa-eye"></i></button>
                                </div>
                                <div id="modalPasswordRequirements" class="hidden mt-2">
                                    <div class="flex flex-wrap gap-3">
                                        <span id="modal-req-length" class="req-badge text-red-500"><i class="fas fa-circle text-xs"></i> 8+ chars</span>
                                        <span id="modal-req-upper" class="req-badge text-red-500"><i class="fas fa-circle text-xs"></i> Uppercase</span>
                                        <span id="modal-req-lower" class="req-badge text-red-500"><i class="fas fa-circle text-xs"></i> Lowercase</span>
                                        <span id="modal-req-number" class="req-badge text-red-500"><i class="fas fa-circle text-xs"></i> Number</span>
                                        <span id="modal-req-special" class="req-badge text-red-500"><i class="fas fa-circle text-xs"></i> Special</span>
                                    </div>
                                    <div class="strength-bar mt-2"></div>
                                </div>
                            </div>
                            

                            <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="modalPasswordConfirm" name="password_confirmation" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pr-16" placeholder="Confirm your password" required>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" onclick="togglePassword(this)"><i class="far fa-eye"></i></button>
                            
                            <!-- PASSWORD MATCH LIGHT - THIS IS THE LIGHT -->
                            <div id="passwordMatchLight" class="absolute right-10 top-1/2 -translate-y-1/2 hidden">
                                <i class="fas fa-check-circle text-green-500 text-base"></i>
                            </div>
                        </div>
                        
                        <!-- Match message below -->
                        <div id="passwordMatchMessage" class="text-xs mt-1 hidden"></div>
                    </div>
                            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition mt-2">Create Account</button>
                        </form>
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">Already have an account? <a href="#" id="switchToLoginBtn" class="text-blue-600 hover:text-blue-700 font-semibold">Login</a></p>
                    </div>
                </div>
                
                <!-- FORGOT PASSWORD FORM - Fixed sizing -->
                @if(session('forgotStep') !== 'verify')
                    <div id="forgotPasswordContainer" style="display: {{ $modalState === 'forgot' ? 'flex' : 'none' }}; width: 100%;">
                        <div class="auth-image forgot-image">
                        <lottie-player src="{{ asset('helpdesk.json') }}" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>
                    </div>
                    <div class="auth-form">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Reset Password</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Enter your email address on file so we can verify your account.</p>
                        <form method="POST" action="{{ route('password.email') }}" id="modalForgotForm">
                            @csrf
                            @if($errors->hasBag('forgot') && ($errors->forgot->any() || session('status')))
                                <div class="mb-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 text-sm">
                                    {{ $errors->forgot->first('email') ?: $errors->forgot->first('phone') ?: session('status') }}
                                </div>
                            @endif
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                <input id="forgotEmail" type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" placeholder="you@example.com">
                            </div>
                                                       <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold hover:from-amber-600 hover:to-orange-700 transition">Find My Account</button>
                        </form>
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                            <a href="#" id="backToLoginFromForgot" class="text-blue-600 hover:text-blue-700"><i class="fas fa-arrow-left mr-1"></i> Back to Login</a>
                        </p>
                    </div>
                </div>
                @endif
                @if(session('forgotStep') === 'verify')
                    <div id="forgotVerifyContainer" style="display: flex; width: 100%;">
                    <div class="auth-image forgot-image">
                        <lottie-player src="{{ asset('helpdesk.json') }}" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>
                    </div>
                    <div class="auth-form">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">We found your account</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Please confirm this is your account before we send the reset link.</p>
                        @php $foundUser = session('foundUser'); @endphp
                        @if($foundUser)
                            <div class="mb-6 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 text-center">
                                <img src="{{ $foundUser['avatar_url'] }}" alt="{{ $foundUser['name'] }}" class="mx-auto h-20 w-20 rounded-full object-cover mb-4">
                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $foundUser['name'] }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $foundUser['method'] === 'sms' ? 'Phone-based reset' : 'Email-based reset' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">If this is you, click below to continue.</p>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <input type="hidden" name="confirm" value="1">
                            <input type="hidden" name="email" value="{{ old('email') }}">
                            <input type="hidden" name="phone" value="{{ old('phone') }}">
                            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold hover:from-emerald-600 hover:to-green-700 transition">Yes, send reset link</button>
                        </form>
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                            <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-700"><i class="fas fa-arrow-left mr-1"></i> Use a different email or phone</a>
                        </p>
                    </div>
                </div>
                @endif
                
                <!-- CONFIRM PASSWORD FORM - Fixed sizing -->
                <div id="confirmPasswordContainer" style="display: none; width: 100%;">
                    <div class="auth-image forgot-image">
                        <lottie-player src="{{ asset('helpdesk.json') }}" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>
                    </div>
                    <div class="auth-form">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Confirm Password</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Please verify your password to continue</p>
                        <div class="mb-4 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500">
                            <p class="text-sm text-amber-700 dark:text-amber-400"><i class="fas fa-shield-alt mr-2"></i> This is a secure area. Please confirm your password before continuing.</p>
                        </div>
                        <form method="POST" action="{{ route('password.confirm.store') }}" id="modalConfirmForm">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pr-10" placeholder="Enter your password" required>
                                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500" onclick="togglePassword(this)"><i class="far fa-eye"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-2 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold hover:from-amber-600 hover:to-orange-700 transition">Confirm Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 50 });
        
        // Scroll handlers
        window.addEventListener('scroll', function() {
            const scrollTop = document.getElementById('scrollTop');
            if (window.scrollY > 300) scrollTop.classList.add('show');
            else scrollTop.classList.remove('show');
        });
        document.getElementById('scrollTop').addEventListener('click', function() { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        
        // Stats AJAX
        $(document).ready(function() {
            $.ajax({ url: '{{ route("welcome.stats") }}', method: 'GET', success: function(data) {
                $('#totalTickets').text(data.total_tickets?.toLocaleString() || '0');
                $('#openTickets').text(data.open_tickets?.toLocaleString() || '0');
                $('#resolvedTickets').text(data.resolved_tickets?.toLocaleString() || '0');
                $('#totalUsers').text(data.total_users?.toLocaleString() || '0');
            }});
        });
        
        // ========== MODAL TRANSITION ==========
        const authModal = document.getElementById('authModal');
        const modalContent = document.getElementById('authModalContent');
        const loginContainer = document.getElementById('loginFormContainer');
        const registerContainer = document.getElementById('registerFormContainer');
        const forgotContainer = document.getElementById('forgotPasswordContainer');
        const confirmContainer = document.getElementById('confirmPasswordContainer');
        
        function getButtonPosition(button) {
            const rect = button.getBoundingClientRect();
            return { x: rect.left + rect.width / 2, y: rect.top + rect.height / 2 };
        }
        
        function openAuthModal(type, buttonElement) {
            const pos = getButtonPosition(buttonElement);
            modalContent.style.setProperty('--origin-x', pos.x + 'px');
            modalContent.style.setProperty('--origin-y', pos.y + 'px');
            
            loginContainer.style.display = 'none';
            registerContainer.style.display = 'none';
            forgotContainer.style.display = 'none';
            confirmContainer.style.display = 'none';
            
            if (type === 'login') loginContainer.style.display = 'flex';
            else if (type === 'register') { registerContainer.style.display = 'flex'; setTimeout(initModalPasswordRequirements, 100); }
            else if (type === 'forgot') forgotContainer.style.display = 'flex';
            else if (type === 'confirm') confirmContainer.style.display = 'flex';
            
            authModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() { authModal.classList.remove('active'); setTimeout(() => { document.body.style.overflow = ''; }, 400); }
        
        window.togglePassword = function(btn) {
            const input = btn.parentElement.querySelector('input');
            const icon = btn.querySelector('i');
            if (input.type === 'password') { input.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
            else { input.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
        };
        
        document.querySelectorAll('.auth-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) { e.preventDefault(); openAuthModal(this.getAttribute('data-auth-type'), this); });
        });
        document.getElementById('closeModal').addEventListener('click', closeModal);
        authModal.addEventListener('click', function(e) { if (e.target === authModal) closeModal(); });
        document.getElementById('switchToRegisterBtn')?.addEventListener('click', function(e) { e.preventDefault(); loginContainer.style.display = 'none'; registerContainer.style.display = 'flex'; initModalPasswordRequirements(); 
            document.querySelectorAll('#loginFormContainer .bg-red-50, #registerFormContainer .bg-red-50, #forgotPasswordContainer .bg-blue-50').forEach(el => el.style.display = 'none');
            document.getElementById('modalRegisterError').classList.add('hidden');
        });
        document.getElementById('switchToLoginBtn')?.addEventListener('click', function(e) { e.preventDefault(); registerContainer.style.display = 'none'; loginContainer.style.display = 'flex'; 
            document.querySelectorAll('#loginFormContainer .bg-red-50, #registerFormContainer .bg-red-50, #forgotPasswordContainer .bg-blue-50').forEach(el => el.style.display = 'none');
            document.getElementById('modalRegisterError').classList.add('hidden');
        });
        document.getElementById('forgotPasswordLink')?.addEventListener('click', function(e) { e.preventDefault(); openAuthModal('forgot', this); });
        document.getElementById('backToLoginFromForgot')?.addEventListener('click', function(e) { e.preventDefault(); forgotContainer.style.display = 'none'; loginContainer.style.display = 'flex'; });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && authModal.classList.contains('active')) closeModal(); });
        
        // Password requirements
        const modalPassword = document.getElementById('modalPassword');
        const modalReqsDiv = document.getElementById('modalPasswordRequirements');
        const modalReqs = {
            length: { regex: /.{8,}/, element: 'modal-req-length', met: false },
            upper: { regex: /[A-Z]/, element: 'modal-req-upper', met: false },
            lower: { regex: /[a-z]/, element: 'modal-req-lower', met: false },
            number: { regex: /[0-9]/, element: 'modal-req-number', met: false },
            special: { regex: /[!@#$%^&*(),.?":{}|<>]/, element: 'modal-req-special', met: false }
        };
        
        function initModalPasswordRequirements() {
            if (modalPassword) {
                modalPassword.addEventListener('focus', () => { if (modalReqsDiv) modalReqsDiv.classList.remove('hidden'); });
                modalPassword.addEventListener('input', function() { validateModalPassword(this.value); });
                document.addEventListener('click', function(e) { if (modalReqsDiv && !modalPassword.contains(e.target) && !modalReqsDiv.contains(e.target)) modalReqsDiv.classList.add('hidden'); });
            }
        }
        
        function validateModalPassword(password) {
            let metCount = 0;
            for (const [key, req] of Object.entries(modalReqs)) {
                const isMet = req.regex.test(password);
                req.met = isMet;
                const element = document.getElementById(req.element);
                if (element) {
                    if (isMet) { element.classList.add('valid'); element.style.color = '#10b981'; element.querySelector('i').className = 'fas fa-check'; metCount++; }
                    else { element.classList.remove('valid'); element.style.color = '#ef4444'; element.querySelector('i').className = 'fas fa-circle'; }
                }
            }
            const bar = document.querySelector('#modalPasswordRequirements .strength-bar');
            if (bar) bar.style.background = `linear-gradient(90deg, #10b981 ${(metCount/5)*100}%, #e5e7eb ${(metCount/5)*100}%)`;
        }
        
        function showModalRegisterError(message) {
            const errorEl = document.getElementById('modalRegisterError');
            if (!errorEl) return;
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function clearModalRegisterError() {
            const errorEl = document.getElementById('modalRegisterError');
            if (!errorEl) return;
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }

        function validateRegisterForm() {
            const password = document.getElementById('modalPassword')?.value || '';
            const confirm = document.getElementById('modalPasswordConfirm')?.value || '';
            const rules = [/.{8,}/, /[A-Z]/, /[a-z]/, /[0-9]/, /[!@#$%^&*(),.?":{}|<>]/];
            const labels = [
                'Password must be at least 8 characters.',
                'Password must include an uppercase letter.',
                'Password must include a lowercase letter.',
                'Password must include a number.',
                'Password must include a special character.',
            ];

            for (let i = 0; i < rules.length; i++) {
                if (!rules[i].test(password)) {
                    showModalRegisterError(labels[i]);
                    return false;
                }
            }

            if (password !== confirm) {
                showModalRegisterError('Password and confirmation do not match.');
                return false;
            }

            clearModalRegisterError();
            return true;
        }

        document.getElementById('modalRegisterForm')?.addEventListener('submit', function(event) {
            if (!validateRegisterForm()) {
                event.preventDefault();
                return false;
            }
        });

        // Ensure styles are applied after page load
        window.addEventListener('load', function() {
            document.body.style.transition = 'background-color 0.15s, color 0.15s';
        });
        @php
            $openRegisterModal = (isset($showRegisterModal) && $showRegisterModal) || session('showRegisterModal') || request()->routeIs('register');
            $openLoginModal = (isset($showLoginModal) && $showLoginModal) || session('showLoginModal') || request()->routeIs('login');
            $openForgotModal = (isset($showForgotModal) && $showForgotModal) || session('showForgotModal') || request()->routeIs('password.request');
        @endphp
        @if($openRegisterModal)
            document.addEventListener('DOMContentLoaded', function() {
                const registerTrigger = document.querySelector('.auth-trigger[data-auth-type="register"]');
                if (typeof openAuthModal === 'function' && registerTrigger) {
                    openAuthModal('register', registerTrigger);
                }
            });
        @elseif($openLoginModal)
            document.addEventListener('DOMContentLoaded', function() {
                const loginTrigger = document.querySelector('.auth-trigger[data-auth-type="login"]');
                if (typeof openAuthModal === 'function' && loginTrigger) {
                    openAuthModal('login', loginTrigger);
                }
            });
        @elseif($openForgotModal)
            document.addEventListener('DOMContentLoaded', function() {
                const loginTrigger = document.querySelector('.auth-trigger[data-auth-type="login"]');
                if (typeof openAuthModal === 'function' && loginTrigger) {
                    openAuthModal('forgot', loginTrigger);
                }
            });
        @endif
    </script>
</body>
</html>