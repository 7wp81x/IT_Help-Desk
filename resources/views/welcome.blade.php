<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IT Helpdesk System') }} - Professional Support Solution</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1E40AF;
            --primary-light: #3B82F6;
            --secondary: #64748B;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
            --dark: #1E293B;
            --light: #F8FAFC;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            overflow-x: hidden;
            color: var(--dark);
        }
        
        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .navbar-brand i {
            background: none;
            -webkit-text-fill-color: var(--primary);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--secondary) !important;
            transition: all 0.3s;
            margin: 0 0.25rem;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
            color: white;
        }
        
        .btn-outline-gradient {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            padding: 120px 0 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.15;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero .lead {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        /* Stats Section */
        .stats-section {
            background: var(--light);
            padding: 60px 0;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--secondary);
            font-weight: 500;
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: var(--secondary);
            font-size: 1.1rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            height: 100%;
            border: 1px solid #e2e8f0;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .feature-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .feature-card h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .feature-card p {
            color: var(--secondary);
            line-height: 1.6;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 80px 0;
            color: white;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
        }
        
        .footer h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .footer a {
            color: #94A3B8;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer a:hover {
            color: white;
        }
        
        .social-links a {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            margin-right: 0.5rem;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero {
                padding: 100px 0 60px;
            }
            
            .section-title h2 {
                font-size: 1.75rem;
            }
            
            .stat-number {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-headset me-2"></i>IT Helpdesk System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#stats">Statistics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item ms-2">
                            <a class="btn btn-gradient" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a class="dropdown-item" href="{{ route('profile.redirect') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="text-white">Streamline Your IT Support Management</h1>
                <p class="lead text-white">
                    Professional help desk ticketing system to manage, track, and resolve IT issues efficiently. 
                    Get 24/7 support for your organization.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-base font-semibold text-blue-700 transition duration-200 hover:bg-blue-50">
                            <i class="fas fa-sign-in-alt"></i> Login portal
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-gradient btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                        <a href="{{ route('agent.apply') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-base font-semibold text-white transition duration-200 hover:from-blue-700 hover:to-blue-800">
                            <i class="fas fa-user-tie"></i>Apply as Agent
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-gradient btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                        <a href="{{ route('user.tickets.create') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Create Ticket
                        </a>
                    @endguest
                </div>
                @guest
                <div class="mt-5 rounded-3xl p-4" style="background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.18); backdrop-filter: blur(10px);">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                        <div>
                            <p class="mb-2 text-sm text-white/80">Need a secure entry point for your team?</p>
                            <h3 class="mb-0 text-white">Open the sign-in suite for users and agents.</h3>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('login') }}" class="btn btn-light text-blue-700" style="background: rgba(255,255,255,0.9); color: #1d4ed8; border: none;">Sign in</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light" style="border-color: rgba(255,255,255,0.65);">Create account</a>
                        </div>
                    </div>
                </div>
                @endguest
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Help Desk" class="img-fluid" style="max-width: 80%; filter: brightness(0) invert(1);">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section id="stats" class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4" data-aos="fade-up">
                <div class="stat-card">
                    <i class="fas fa-ticket-alt fa-3x mb-3" style="color: var(--primary);"></i>
                    <div class="stat-number" id="totalTickets">0</div>
                    <div class="stat-label">Total Tickets</div>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <i class="fas fa-spinner fa-3x mb-3" style="color: var(--warning);"></i>
                    <div class="stat-number" id="openTickets">0</div>
                    <div class="stat-label">Open Tickets</div>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <i class="fas fa-check-circle fa-3x mb-3" style="color: var(--success);"></i>
                    <div class="stat-number" id="resolvedTickets">0</div>
                    <div class="stat-label">Resolved Tickets</div>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <i class="fas fa-users fa-3x mb-3" style="color: var(--info);"></i>
                    <div class="stat-number" id="totalUsers">0</div>
                    <div class="stat-label">Active Users</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Why Choose Our Help Desk?</h2>
            <p>Powerful features to streamline your IT support process</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Fast Response</h4>
                    <p>Quick ticket routing and assignment to the right support team members.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Analytics Dashboard</h4>
                    <p>Real-time insights and reports on ticket performance and team efficiency.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Secure & Reliable</h4>
                    <p>Enterprise-grade security with role-based access control and data encryption.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-paperclip"></i>
                    </div>
                    <h4>File Attachments</h4>
                    <p>Upload screenshots, documents, and logs directly to tickets.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email Notifications</h4>
                    <p>Automatic email updates on ticket status changes and responses.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Mobile Friendly</h4>
                    <p>Fully responsive design accessible from any device.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div data-aos="fade-up">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of organizations using our help desk system</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Create Free Account
                </a>
            @else
                <a href="{{ route('user.tickets.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-ticket-alt me-2"></i>Submit a Ticket
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-headset me-2"></i>IT Helpdesk System</h5>
                <p class="text-muted">Professional IT support ticketing system for modern organizations.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#stats">Statistics</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Support</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Reference</a></li>
                    <li><a href="#">System Status</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i>support@helpdesk.com</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</li>
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>123 IT Street, Tech City</li>
                </ul>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} IT Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.05)';
        }
    });
    
    // Load statistics from backend
    $(document).ready(function() {
        $.ajax({
            url: '{{ route("welcome.stats") }}',
            method: 'GET',
            success: function(data) {
                $('#totalTickets').text(data.total_tickets.toLocaleString());
                $('#openTickets').text(data.open_tickets.toLocaleString());
                $('#resolvedTickets').text(data.resolved_tickets.toLocaleString());
                $('#totalUsers').text(data.total_users.toLocaleString());
                
                // Animate numbers
                $('.stat-number').each(function() {
                    const $this = $(this);
                    const target = parseInt($this.text());
                    let current = 0;
                    const increment = target / 50;
                    const timer = setInterval(function() {
                        current += increment;
                        if (current >= target) {
                            $this.text(target.toLocaleString());
                            clearInterval(timer);
                        } else {
                            $this.text(Math.floor(current).toLocaleString());
                        }
                    }, 20);
                });
            },
            error: function() {
                console.log('Failed to load statistics');
            }
        });
    });
</script>
</body>
</html>