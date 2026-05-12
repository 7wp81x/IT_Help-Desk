<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IT Helpdesk') }}</title>

    <!-- CRITICAL FIX: Force dark mode colors BEFORE anything loads -->
    <style>
        /* Immediate dark mode fix - prevents white flash */
        html.dark body {
            background-color: #111827 !important;
        }
        html.dark .main-content {
            background-color: #111827 !important;
        }
        html.dark .bg-white {
            background-color: #1f2937 !important;
        }
        html.dark .bg-gray-50 {
            background-color: #111827 !important;
        }
        html.dark .bg-gray-100 {
            background-color: #1f2937 !important;
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
        html.dark .border-gray-100 {
            border-color: #374151 !important;
        }
        html.dark .shadow-sm {
            --tw-shadow-color: rgba(0, 0, 0, 0.3);
        }
        /* Fix for stats cards */
        html.dark .rounded-lg.bg-white {
            background-color: #1f2937 !important;
        }
        /* Fix for hover states */
        html.dark .hover\:bg-gray-50:hover {
            background-color: #374151 !important;
        }
    </style>

    <!-- Immediate dark mode application (before any rendering) -->
    <script>
        (function() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            const htmlRoot = document.documentElement;
            
            if (darkMode) {
                htmlRoot.classList.add('dark');
                document.documentElement.style.backgroundColor = '#111827';
                document.body.style.backgroundColor = '#111827';
            } else {
                htmlRoot.classList.remove('dark');
                document.documentElement.style.backgroundColor = '#f9fafb';
                document.body.style.backgroundColor = '#f9fafb';
            }
            
            window.pageLoaded = false;
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
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        
        .main-content {
            margin-left: 280px;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-active { overflow: hidden; position: fixed; width: 100%; height: 100%; }
            button, a { cursor: pointer; -webkit-tap-highlight-color: transparent; }
            .main-content {
                margin-left: 0 !important;
            }
        }
        
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-track { background: #1f2937; }
        .dark ::-webkit-scrollbar-thumb { background: #4b5563; }
        
        .mobile-menu-btn {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 50;
            width: 2.5rem;
            height: 2.5rem;
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #1a2c3e 0%, #2c4a6e 100%);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .mobile-menu-btn:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #2c4a6e 0%, #1a2c3e 100%);
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 0.6s linear infinite;
        }
        
        /* Force sidebar dark mode styles */
        #default-sidebar,
        #default-sidebar * {
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
        }
        
        html.dark #default-sidebar,
        html.dark .sidebar-container,
        html.dark [class*="sidebar"] {
            background-color: #1f2937 !important;
            border-right-color: #374151 !important;
        }
        
        html.dark #default-sidebar a,
        html.dark .sidebar-link {
            color: #e5e7eb !important;
        }
        
        html.dark #default-sidebar a:hover,
        html.dark .sidebar-link:hover {
            background-color: #374151 !important;
            color: white !important;
        }
        
        html.dark #default-sidebar .active,
        html.dark .sidebar-link.active {
            background-color: #2563eb !important;
            color: white !important;
        }
        
        html.dark .main-content {
            background-color: #111827 !important;
        }
        
        /* Loading animation */
        #pageLoadingOverlay {
            pointer-events: none;
        }
        
        #pageLoadingOverlay.pointer-events-auto {
            pointer-events: auto;
        }
        
        /* ADMIN LOADING SCREEN */
        #admin-loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: system-ui;
            transition: opacity 0.3s ease;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        
        #admin-loader.visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        
        .admin-loader-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .admin-loader-logo i {
            font-size: 4rem;
            color: #3b82f6;
            margin-bottom: 1rem;
        }
        
        .admin-loader-logo h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            letter-spacing: 1px;
        }
        
        .admin-loader-logo p {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.6);
            margin-top: 0.5rem;
        }
        
        .admin-loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(59,130,246,0.3);
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        .admin-loader-text {
            margin-top: 1rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
        }
        
        #admin-loader.fade-out {
            opacity: 0;
            pointer-events: none;
        }
    </style>
    
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-150"
      x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        mobileMenuOpen: false,
        isMobile: window.innerWidth < 768
      }"
      x-init="
        const applyTheme = (isDark) => {
            if(isDark) {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#111827';
                document.body.style.backgroundColor = '#111827';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.style.backgroundColor = '#f9fafb';
                document.body.style.backgroundColor = '#f9fafb';
            }
        };
        
        applyTheme(darkMode);
        
        $watch('darkMode', val => {
          localStorage.setItem('darkMode', val);
          applyTheme(val);
          window.dispatchEvent(new CustomEvent('theme-toggle', { detail: { darkMode: val } }));
        });
        
        $watch('sidebarCollapsed', val => {
          localStorage.setItem('sidebarCollapsed', val);
          window.dispatchEvent(new CustomEvent('sidebar-toggle', { detail: { collapsed: val } }));
        });
        
        window.addEventListener('theme-toggle', (event) => {
          if (event.detail.darkMode !== undefined && event.detail.darkMode !== darkMode) {
            darkMode = event.detail.darkMode;
          }
        });
        
        window.addEventListener('sidebar-toggle', (event) => {
          if (event.detail.collapsed !== undefined && event.detail.collapsed !== sidebarCollapsed) {
            sidebarCollapsed = event.detail.collapsed;
          }
        });
        
        const sidebar = document.getElementById('default-sidebar');
        if (isMobile && sidebar && !mobileMenuOpen) {
            sidebar.style.transform = 'translateX(-100%)';
        }
        
        window.addEventListener('resize', () => {
            isMobile = window.innerWidth < 768;
            const sidebar = document.getElementById('default-sidebar');
            if (!isMobile) {
                mobileMenuOpen = false;
                if (sidebar) sidebar.style.transform = '';
                if (sidebarCollapsed) {
                    document.querySelector('.main-content').style.marginLeft = '80px';
                } else {
                    document.querySelector('.main-content').style.marginLeft = '280px';
                }
            } else if (sidebar && !mobileMenuOpen) {
                sidebar.style.transform = 'translateX(-100%)';
                document.querySelector('.main-content').style.marginLeft = '0px';
            }
        });
        
        document.addEventListener('click', function(event) {
            if (mobileMenuOpen && isMobile) {
                const sidebar = document.getElementById('default-sidebar');
                const menuButton = document.getElementById('mobile-menu-button');
                if (sidebar && !sidebar.contains(event.target) && menuButton && !menuButton.contains(event.target)) {
                    mobileMenuOpen = false;
                    if (sidebar) sidebar.style.transform = 'translateX(-100%)';
                }
            }
        });
      ">

    <!-- ADMIN LOADING SCREEN -->
    <div id="admin-loader">
        <div class="admin-loader-logo">
            <i class="fas fa-headset"></i>
            <h2>IT Helpdesk System</h2>
            <p>Admin Dashboard</p>
        </div>
        <div class="admin-loader-spinner"></div>
        <div class="admin-loader-text">Logging you in...</div>
    </div>
    
    <script>
        (function() {
            const loader = document.getElementById('admin-loader');
            
            window.showLoginLoader = function() {
                if (loader) {
                    loader.classList.add('visible');
                    loader.classList.remove('fade-out');
                }
            };
            
            window.hideLoginLoader = function() {
                if (loader) {
                    loader.classList.add('fade-out');
                    setTimeout(function() {
                        loader.classList.remove('visible', 'fade-out');
                    }, 300);
                }
            };
            
            document.addEventListener('submit', function(e) {
                const form = e.target;
                const hasEmail = form.querySelector('input[type="email"], input[name="email"]');
                const hasPassword = form.querySelector('input[type="password"], input[name="password"]');
                const isLoginPage = window.location.pathname.includes('login');
                
                if ((hasEmail && hasPassword) || isLoginPage) {
                    setTimeout(function() {
                        showLoginLoader();
                    }, 50);
                }
            });
            
            const isAdminPage = window.location.pathname.match(/^\/admin(\/|$)/) || 
                               window.location.pathname.match(/^\/dashboard(\/|$)/) ||
                               window.location.pathname.match(/^\/users(\/|$)/) ||
                               window.location.pathname.match(/^\/agents(\/|$)/);
            
            if (isAdminPage && document.referrer && document.referrer.includes('login')) {
                showLoginLoader();
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        hideLoginLoader();
                    }, 800);
                });
            }
        })();
    </script>

    {{-- Sidebar --}}
    @auth
        @if(Auth::user()->role === 'admin')
            @include('layouts.admin.sidebar')
        @elseif(Auth::user()->role === 'agent')
            @include('layouts.agent.sidebar')
        @else
            @include('layouts.user.sidebar')
        @endif
    @endauth

    {{-- Main Content --}}
    <div class="main-content min-h-screen bg-gray-50 dark:bg-gray-900"
         :style="!isMobile && sidebarCollapsed ? 'margin-left: 80px;' : (!isMobile && !sidebarCollapsed ? 'margin-left: 280px;' : 'margin-left: 0;')">

        {{-- Mobile Menu Button --}}
        <button id="mobile-menu-button"
                class="mobile-menu-btn md:hidden"
                @click="mobileMenuOpen = !mobileMenuOpen; if(mobileMenuOpen) { sidebarCollapsed = false; document.getElementById('default-sidebar').style.transform = 'translateX(0)'; } else { document.getElementById('default-sidebar').style.transform = 'translateX(-100%)'; }">
            <i class="bi bi-list text-2xl"></i>
        </button>

        {{-- Header Section --}}
        @hasSection('header')
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
            <div class="px-4 md:px-6 py-4 md:py-6">
                @yield('header')
            </div>
        </header>
        @endif

        {{-- Page Content --}}
        <main class="p-4 md:p-6">
            @yield('content')
        </main>

    </div>

    {{-- Page Loading Overlay --}}
    <div id="pageLoadingOverlay" class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 invisible transition-opacity duration-300 bg-gray-50/95 dark:bg-gray-900/95">
        <div class="rounded-2xl shadow-2xl p-6 flex flex-col items-center gap-3 min-w-[200px] bg-white dark:bg-gray-800">
            <div class="relative w-12 h-12">
                <div class="absolute inset-0 border-4 rounded-full border-gray-200 dark:border-gray-700"></div>
                <div class="absolute inset-0 border-4 border-t-transparent rounded-full animate-spin border-blue-600"></div>
            </div>
            <p class="font-medium text-sm text-gray-700 dark:text-gray-300">Loading...</p>
        </div>
    </div>

<script>
(function () {
    const overlay = document.getElementById('pageLoadingOverlay');
    if (!overlay) return;

    let isVisible = false;

    function showOverlay() {
        isVisible = true;
        overlay.classList.remove('opacity-0', 'invisible');
        overlay.classList.add('opacity-100', 'visible');
    }

    function hideOverlay() {
        overlay.classList.remove('opacity-100', 'visible');
        overlay.classList.add('opacity-0', 'invisible');
        isVisible = false;
    }

    window.addEventListener('load', hideOverlay);
    window.showPageLoader = showOverlay;
    window.hidePageLoader = hideOverlay;

})();
</script>

<!-- FIX: Theme manager that syncs everything -->
<script>
(function() {
    // Listen for theme toggle events from sidebar
    window.addEventListener('theme-toggle', function(e) {
        const isDark = document.documentElement.classList.contains('dark');
        const newTheme = !isDark;
        
        if (newTheme) {
            document.documentElement.classList.add('dark');
            document.documentElement.style.backgroundColor = '#111827';
            document.body.style.backgroundColor = '#111827';
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.backgroundColor = '#f9fafb';
            document.body.style.backgroundColor = '#f9fafb';
        }
        
        localStorage.setItem('darkMode', newTheme);
        
        // Dispatch event for Alpine
        window.dispatchEvent(new CustomEvent('theme-toggle-sync', { detail: { darkMode: newTheme } }));
    });
    
    // Also listen for the sync event
    window.addEventListener('theme-toggle-sync', function(e) {
        if (e.detail && typeof e.detail.darkMode !== 'undefined') {
            // Alpine will pick this up
        }
    });
})();
</script>

    @stack('scripts')
    @stack('modals')
</body>
<script>
window.addEventListener('load', () => {
    document.documentElement.classList.add('loaded');
});
</script>
</html>