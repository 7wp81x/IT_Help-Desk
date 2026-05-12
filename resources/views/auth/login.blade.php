@extends('layouts.guest')

@section('title', 'Login')
@section('header', 'Welcome Back')
@section('subheader', 'Sign in to continue to your account')

@section('content')
<div class="row d-flex justify-content-center align-items-center">
    <div class="col-md-12">
        <div class="mb-4 rounded-3xl border border-gray-200 bg-gray-50 p-4" style="border-radius: 1rem; background: rgba(248,250,252,0.95); border-color: #e2e8f0;">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                <div>
                    <h5 class="mb-1" style="color: #1f2937; font-weight: 700;">Welcome back</h5>
                    <p class="mb-0" style="color: #475569; font-size: 0.9rem;">Return to the homepage to review platform highlights before signing in.</p>
                </div>
                <a href="{{ route('welcome') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #2563EB, #1D4ED8); border: none; padding: 0.65rem 1.25rem;">
                    <i class="fas fa-home me-2"></i>Back to Welcome
                </a>
            </div>
        </div>
          <!-- Social Register Buttons -->
        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-3" style="display: none;">
            <p class="fw-normal mb-0 me-2" style="color: var(--text-primary); font-size: 0.75rem;">Sign up with</p>
            <button type="button" class="btn-floating mx-1" id="socialFacebookBtn" style="border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color); font-size: 0.8rem;">
                <i class="fab fa-facebook-f"></i>
            </button>
            <button type="button" class="btn-floating mx-1" id="socialTwitterBtn" style="border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color); font-size: 0.8rem;">
                <i class="fab fa-twitter"></i>
            </button>
            <button type="button" class="btn-floating mx-1" id="socialLinkedinBtn" style="border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color); font-size: 0.8rem;">
                <i class="fab fa-linkedin-in"></i>
            </button>
        </div>

        <!-- Divider -->
        <div class="divider d-flex align-items-center my-4">
            <hr class="grow" style="border-color: var(--border-color);">
            <p class="text-center fw-bold mx-3 mb-0" style="color: var(--text-secondary);">Or</p>
            <hr class="grow" style="border-color: var(--border-color);">
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email input - EMPTY FIELD (no default value) -->
            <div class="mb-4">
                <label for="email" class="form-label" style="color: var(--text-primary);">Email address</label>
                <input type="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                       placeholder="Enter your email address" style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border);">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="invalid-feedback" id="clientEmailError"></div>
            </div>

            <!-- Password input with Eye Icon - EMPTY FIELD (no default value) -->
            <div class="mb-3">
                <label for="password" class="form-label" style="color: var(--text-primary);">Password</label>
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password"
                           placeholder="Enter your password"
                           style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); padding-right: 45px;">
                    <button type="button" class="password-toggle" id="togglePassword" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); 
                                   background: transparent; border: none; cursor: pointer; color: var(--text-secondary); font-size: 1.1rem;"
                            tabindex="-1">
                        <i class="far fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="invalid-feedback" id="clientPasswordError"></div>
            </div>

            <!-- Remember me and Forgot password - PERFECTLY ALIGNED -->
            <div class="remember-forgot-row">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                           style="background-color: var(--bg-input); border-color: var(--input-border);">
                    <label class="form-check-label" for="remember" style="color: var(--text-primary);">
                        Remember me
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" id="forgotPasswordLink" class="text-decoration-none" style="color: var(--primary-light);">
                        Forgot password?
                    </a>
                @else
                    <a href="#" id="forgotPasswordLink" class="text-decoration-none" style="color: var(--primary-light);">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="text-center text-lg-start mt-4 pt-2">
                <button type="submit" class="btn btn-primary btn-lg" id="loginSubmitBtn" 
                        style="padding-left: 2.5rem; padding-right: 2.5rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
                <p class="small fw-bold mt-2 pt-1 mb-0" style="color: var(--text-secondary);">
                    Don't have an account? 
                    <a href="{{ route('register') }}" id="registerLink" class="text-decoration-none" style="color: var(--primary-light);">Register</a>
                </p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== PASSWORD EYE TOGGLE - FULLY FUNCTIONAL ==========
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (togglePasswordBtn && passwordField) {
            togglePasswordBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }
        
        // ========== REMEMBER ME FUNCTIONALITY ==========
        const rememberCheckbox = document.getElementById('remember');
        const emailInput = document.getElementById('email');
        
        // Load saved email if remember me was checked previously (only if field is empty)
        if (localStorage.getItem('rememberedEmail') && localStorage.getItem('rememberChecked') === 'true') {
            if (emailInput && (!emailInput.value || emailInput.value === '')) {
                emailInput.value = localStorage.getItem('rememberedEmail');
            }
            if (rememberCheckbox) {
                rememberCheckbox.checked = true;
            }
        }
        
        // ========== CLIENT-SIDE VALIDATION & REMEMBER ME SAVE ==========
        const loginForm = document.getElementById('loginForm');
        const clientEmailError = document.getElementById('clientEmailError');
        const clientPasswordError = document.getElementById('clientPasswordError');
        
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                let isValid = true;
                const email = emailInput ? emailInput.value.trim() : '';
                const password = passwordField ? passwordField.value.trim() : '';
                
                // Reset client-side errors
                if (clientEmailError) clientEmailError.innerText = '';
                if (clientPasswordError) clientPasswordError.innerText = '';
                if (emailInput) emailInput.classList.remove('is-invalid');
                if (passwordField) passwordField.classList.remove('is-invalid');
                
                // Email validation
                if (!email) {
                    if (clientEmailError) clientEmailError.innerText = 'Email address is required.';
                    if (emailInput) emailInput.classList.add('is-invalid');
                    isValid = false;
                } else if (!email.includes('@') || !email.includes('.')) {
                    if (clientEmailError) clientEmailError.innerText = 'Please enter a valid email address.';
                    if (emailInput) emailInput.classList.add('is-invalid');
                    isValid = false;
                }
                
                // Password validation
                if (!password) {
                    if (clientPasswordError) clientPasswordError.innerText = 'Password is required.';
                    if (passwordField) passwordField.classList.add('is-invalid');
                    isValid = false;
                } else if (password.length < 4) {
                    if (clientPasswordError) clientPasswordError.innerText = 'Password must be at least 4 characters.';
                    if (passwordField) passwordField.classList.add('is-invalid');
                    isValid = false;
                }
                
                // Handle Remember Me storage
                if (isValid && rememberCheckbox) {
                    if (rememberCheckbox.checked) {
                        localStorage.setItem('rememberedEmail', email);
                        localStorage.setItem('rememberChecked', 'true');
                    } else {
                        localStorage.removeItem('rememberedEmail');
                        localStorage.setItem('rememberChecked', 'false');
                    }
                }
                
                // If validation fails, prevent form submission
                if (!isValid) {
                    e.preventDefault();
                }
                // If valid, let the form submit normally to Laravel route
            });
        }
        
        // ========== FORGOT PASSWORD HANDLER ==========
        const forgotLink = document.getElementById('forgotPasswordLink');
        if (forgotLink && (!forgotLink.getAttribute('href') || forgotLink.getAttribute('href') === '#')) {
            forgotLink.addEventListener('click', function(e) {
                e.preventDefault();
                showDemoMessage('Password reset link would be sent to your email address.', 'warning');
            });
        }
        
        // ========== REGISTER HANDLER ==========
        const registerLinkElem = document.getElementById('registerLink');
        if (registerLinkElem && registerLinkElem.getAttribute('href') === '#') {
            registerLinkElem.addEventListener('click', function(e) {
                e.preventDefault();
                showDemoMessage('Registration page - connect to your backend route.', 'info');
            });
        }
        
        // ========== SOCIAL BUTTONS DEMO ==========
        const socialFacebook = document.getElementById('socialFacebookBtn');
        const socialTwitter = document.getElementById('socialTwitterBtn');
        const socialLinkedin = document.getElementById('socialLinkedinBtn');
        
        if (socialFacebook) {
            socialFacebook.addEventListener('click', () => showDemoMessage('Connecting with Facebook (demo)', 'info'));
        }
        if (socialTwitter) {
            socialTwitter.addEventListener('click', () => showDemoMessage('Connecting with Twitter (demo)', 'info'));
        }
        if (socialLinkedin) {
            socialLinkedin.addEventListener('click', () => showDemoMessage('Connecting with LinkedIn (demo)', 'info'));
        }
        
        function showDemoMessage(message, type) {
            // Remove existing dynamic alert
            const existingAlert = document.querySelector('.auth-form .dynamic-alert');
            if(existingAlert) existingAlert.remove();
            
            const alertDiv = document.createElement('div');
            let icon = 'fa-info-circle';
            if(type === 'success') icon = 'fa-check-circle';
            if(type === 'warning') icon = 'fa-exclamation-triangle';
            if(type === 'danger') icon = 'fa-exclamation-circle';
            
            alertDiv.className = `alert alert-${type === 'info' ? 'warning' : type} dynamic-alert`;
            alertDiv.style.cssText = 'border-radius: 10px; margin-bottom: 0.8rem; padding: 0.5rem 0.85rem; font-size: 0.7rem; font-weight: 500;';
            alertDiv.innerHTML = `<i class="fas ${icon} me-2"></i> ${message}`;
            
            const formContainer = document.querySelector('.auth-form');
            const firstElement = formContainer.firstChild;
            formContainer.insertBefore(alertDiv, firstElement);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }
    });
</script>
@endpush
@endsection