@extends('layouts.guest')

@section('title', 'Register')
@section('header', 'Create Account')
@section('subheader', 'Join our IT Helpdesk System')

@section('content')
<div class="row d-flex justify-content-center align-items-center">
    <div class="col-md-12">
        <div class="mb-4 rounded-3xl border border-gray-200 bg-gray-50 p-4" style="border-radius: 1rem; background: rgba(248,250,252,0.95); border-color: #e2e8f0;">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                <div>
                    <h5 class="mb-1" style="color: #1f2937; font-weight: 700;">Need more context?</h5>
                    <p class="mb-0" style="color: #475569; font-size: 0.9rem;">Explore the welcome page first, then return to create your account with confidence.</p>
                </div>
                <a href="{{ route('welcome') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #2563EB, #1D4ED8); border: none; padding: 0.65rem 1.25rem;">
                    <i class="fas fa-home me-2"></i>Back to Welcome
                </a>
            </div>
        </div>
        <!-- Social Register Buttons -->
        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-3">
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
        <div class="divider d-flex align-items-center my-3">
            <hr class="grow" style="border-color: var(--border-color);">
            <p class="text-center fw-bold mx-2 mb-0" style="color: var(--text-secondary); font-size: 0.7rem;">Or</p>
            <hr class="grow" style="border-color: var(--border-color);">
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- ========== USER TYPE SELECTION ========== -->
            <div class="mb-4">
                <label class="form-label" style="color: var(--text-primary); font-size: 0.75rem;">Account Type *</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="roleUser" 
                               value="user" {{ old('role', request()->query('role', 'user')) === 'user' ? 'checked' : '' }} onchange="toggleUserFields()">
                        <label class="form-check-label" for="roleUser" style="color: var(--text-primary); font-size: 0.75rem;">
                            <i class="fas fa-user"></i> Regular User
                            <small class="d-block text-muted" style="font-size: 0.65rem;">Submit and track tickets</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="roleAgent" 
                               value="agent" {{ old('role', request()->query('role')) === 'agent' ? 'checked' : '' }} onchange="toggleUserFields()">
                        <label class="form-check-label" for="roleAgent" style="color: var(--text-primary); font-size: 0.75rem;">
                            <i class="fas fa-headset"></i> Support Agent
                            <small class="d-block text-muted" style="font-size: 0.65rem;">Handle customer tickets</small>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ========== COMMON FIELDS (All Users) ========== -->
            <!-- Name input -->
            <div class="mb-3">
                <label for="name" class="form-label" style="color: var(--text-primary); font-size: 0.75rem;">Full Name *</label>
                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                       placeholder="Enter your full name" style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); font-size: 0.8rem; padding: 0.45rem 0.8rem;">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="invalid-feedback" id="clientNameError"></div>
            </div>

            <!-- Email input -->
            <div class="mb-3">
                <label for="email" class="form-label" style="color: var(--text-primary); font-size: 0.75rem;">Email address *</label>
                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email', request()->query('email')) }}" required autocomplete="email"
                       placeholder="Enter your email address" style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); font-size: 0.8rem; padding: 0.45rem 0.8rem;">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="invalid-feedback" id="clientEmailError"></div>
            </div>

            <!-- Phone input -->
            <div class="mb-3">
                <label for="phone" class="form-label" style="color: var(--text-primary); font-size: 0.75rem;">Phone Number *</label>
                <input type="tel" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       name="phone" value="{{ old('phone') }}" required
                       placeholder="Enter your phone number" style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); font-size: 0.8rem; padding: 0.45rem 0.8rem;">
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="invalid-feedback" id="clientPhoneError"></div>
            </div>

            <!-- Password input with Eye Icon -->
            <div class="mb-2">
                <label for="password" class="form-label" style="color: var(--text-primary); font-size: 0.75rem;">Password *</label>
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password"
                           placeholder="Create a password (min. 8 characters)"
                           style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); font-size: 0.8rem; padding: 0.45rem 0.8rem; padding-right: 40px;">
                    <button type="button" class="password-toggle" id="togglePassword" 
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); 
                                   background: transparent; border: none; cursor: pointer; color: var(--text-secondary); font-size: 0.9rem;"
                            tabindex="-1">
                        <i class="far fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                
                <!-- Password Requirements -->
                <div id="passwordRequirements" class="password-reqs-line" style="display: none; margin-top: 0.3rem;">
                    <div class="reqs-wrapper" style="display: flex; flex-wrap: wrap; gap: 0.6rem; align-items: center;">
                        <span id="req-length" class="req-badge" style="font-size: 0.6rem; color: #ef4444; transition: all 0.2s;">
                            <i class="fas fa-circle me-1" style="font-size: 0.35rem; vertical-align: middle;"></i> 8+ characters
                        </span>
                        <span id="req-upper" class="req-badge" style="font-size: 0.6rem; color: #ef4444; transition: all 0.2s;">
                            <i class="fas fa-circle me-1" style="font-size: 0.35rem; vertical-align: middle;"></i> Uppercase
                        </span>
                        <span id="req-lower" class="req-badge" style="font-size: 0.6rem; color: #ef4444; transition: all 0.2s;">
                            <i class="fas fa-circle me-1" style="font-size: 0.35rem; vertical-align: middle;"></i> Lowercase
                        </span>
                        <span id="req-number" class="req-badge" style="font-size: 0.6rem; color: #ef4444; transition: all 0.2s;">
                            <i class="fas fa-circle me-1" style="font-size: 0.35rem; vertical-align: middle;"></i> Number
                        </span>
                        <span id="req-special" class="req-badge" style="font-size: 0.6rem; color: #ef4444; transition: all 0.2s;">
                            <i class="fas fa-circle me-1" style="font-size: 0.35rem; vertical-align: middle;"></i> Special character
                        </span>
                    </div>
                    <div class="strength-bar" style="height: 2px; border-radius: 2px; margin-top: 0.4rem; background: var(--border-color); transition: all 0.2s ease;"></div>
                </div>
                
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="invalid-feedback" id="clientPasswordError"></div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password-confirm" class="form-label" style="color: var(--text-primary); font-size: 0.75rem;">Confirm Password *</label>
                <div class="password-wrapper" style="position: relative;">
                    <input type="password" id="password-confirm" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Confirm your password"
                           style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); font-size: 0.8rem; padding: 0.45rem 0.8rem; padding-right: 40px;">
                    <button type="button" class="password-toggle" id="toggleConfirmPassword" 
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); 
                                   background: transparent; border: none; cursor: pointer; color: var(--text-secondary); font-size: 0.9rem;"
                            tabindex="-1">
                        <i class="far fa-eye" id="eyeIconConfirm"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="clientConfirmError"></div>
            </div>


            <!-- ========== AGENT FIELDS ONLY ========== -->
            <div id="agentFields" style="display: none;">
                <div class="border-top pt-2 mt-2">
                    <h6 class="mb-2" style="color: var(--text-primary); font-size: 0.75rem;">Agent Information</h6>
                    
                    <div class="mb-2">
                        <label for="employee_id" class="form-label" style="color: var(--text-primary); font-size: 0.7rem;">Employee ID</label>
                        <input type="text" class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" 
                            value="{{ old('employee_id') }}"
                            placeholder="AGT-XXXX"
                            style="background: var(--bg-input); color: var(--text-primary); border-color: var(--input-border); font-size: 0.75rem; padding: 0.4rem 0.7rem;">
                        @error('employee_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="invalid-feedback" id="clientEmployeeIdError"></div>
                        <p class="mt-2 text-xs text-gray-500">Optional: enter your employee ID only if the admin already assigned one. Otherwise leave it blank and it will be generated once your application is approved.</p>
                    </div>
                </div>
            </div>

            <!-- Register Button -->
            <div class="text-center text-lg-start mt-3 pt-1">
                <button type="submit" class="btn btn-primary" id="registerSubmitBtn" 
                        style="padding-left: 1.8rem; padding-right: 1.8rem; padding-top: 0.45rem; padding-bottom: 0.45rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; font-size: 0.8rem;">
                    <i class="fas fa-user-plus me-1"></i> Register
                </button>
                <p class="small fw-bold mt-2 mb-0" style="color: var(--text-secondary); font-size: 0.7rem;">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary-light);">Login</a>
                </p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
    .req-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        transition: all 0.2s ease;
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
    
    .req-badge i {
        font-size: 0.35rem;
        vertical-align: middle;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== TOGGLE USER FIELDS BASED ON SELECTION ==========
      window.toggleUserFields = function() {
            const role = document.querySelector('input[name="role"]:checked').value;
            const userFields = document.getElementById('userFields');
            const agentFields = document.getElementById('agentFields');
            const socialButtons = document.querySelector('.d-flex.flex-row.align-items-center.justify-content-center.justify-content-lg-start.mb-3');
            const submitBtn = document.getElementById('registerSubmitBtn');
            
            if (role === 'user') {
                // Regular User - Show social buttons
                if (userFields) userFields.style.display = 'none';
                if (agentFields) agentFields.style.display = 'none';
                if (socialButtons) socialButtons.style.display = 'flex';
                submitBtn.innerHTML = '<i class="fas fa-user me-1"></i> Register as User';
            } else {
                // Agent - Hide social buttons
                if (userFields) userFields.style.display = 'none';
                if (agentFields) agentFields.style.display = 'block';
                if (socialButtons) socialButtons.style.display = 'none';
                submitBtn.innerHTML = '<i class="fas fa-headset me-1"></i> Register as Agent';
            }
        };
        
        // ========== PASSWORD EYE TOGGLE ==========
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
        
        const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
        const confirmField = document.getElementById('password-confirm');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        
        if (toggleConfirmBtn && confirmField) {
            toggleConfirmBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = confirmField.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmField.setAttribute('type', type);
                eyeIconConfirm.classList.toggle('fa-eye');
                eyeIconConfirm.classList.toggle('fa-eye-slash');
            });
        }
        
        // ========== PASSWORD REQUIREMENTS ==========
        const requirementsLine = document.getElementById('passwordRequirements');
        
        if (passwordField && requirementsLine) {
            passwordField.addEventListener('focus', function() {
                requirementsLine.style.display = 'block';
            });
            
            document.addEventListener('click', function(e) {
                if (!passwordField.contains(e.target) && !requirementsLine.contains(e.target)) {
                    requirementsLine.style.display = 'none';
                }
            });
            
            requirementsLine.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        const requirements = {
            length: { regex: /.{8,}/, element: 'req-length', met: false },
            upper: { regex: /[A-Z]/, element: 'req-upper', met: false },
            lower: { regex: /[a-z]/, element: 'req-lower', met: false },
            number: { regex: /[0-9]/, element: 'req-number', met: false },
            special: { regex: /[!@#$%^&*(),.?":{}|<>]/, element: 'req-special', met: false }
        };
        
        function validatePassword(password) {
            let allMet = true;
            for (const [key, req] of Object.entries(requirements)) {
                const isMet = req.regex.test(password);
                req.met = isMet;
                const element = document.getElementById(req.element);
                if (element) {
                    if (isMet) {
                        element.classList.add('valid');
                    } else {
                        element.classList.remove('valid');
                    }
                }
                if (!isMet) allMet = false;
            }
            return allMet;
        }
        
        function updateStrengthBar() {
            const strengthBar = document.querySelector('.strength-bar');
            if (!strengthBar) return;
            let metCount = 0;
            for (const req of Object.values(requirements)) {
                if (req.met) metCount++;
            }
            const percentage = (metCount / 5) * 100;
            strengthBar.style.background = `linear-gradient(90deg, #10b981 ${percentage}%, var(--border-color) ${percentage}%)`;
        }
        
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                validatePassword(this.value);
                updateStrengthBar();
                if (confirmField && confirmField.value) {
                    validateConfirmPassword();
                }
            });
        }
        
        function validateConfirmPassword() {
            const password = passwordField ? passwordField.value : '';
            const confirm = confirmField ? confirmField.value : '';
            const confirmError = document.getElementById('clientConfirmError');
            
            if (confirmField) {
                if (confirm === '') {
                    if (confirmError) confirmError.innerText = '';
                    confirmField.classList.remove('is-invalid');
                    return false;
                } else if (password !== confirm) {
                    if (confirmError) confirmError.innerText = 'Passwords do not match.';
                    confirmField.classList.add('is-invalid');
                    return false;
                } else {
                    if (confirmError) confirmError.innerText = '';
                    confirmField.classList.remove('is-invalid');
                    return true;
                }
            }
            return false;
        }
        
        if (confirmField) {
            confirmField.addEventListener('input', validateConfirmPassword);
        }
        
        // ========== FORM VALIDATION ==========
        const registerForm = document.getElementById('registerForm');
        const nameField = document.getElementById('name');
        const emailField = document.getElementById('email');
        const phoneField = document.getElementById('phone');
        const clientNameError = document.getElementById('clientNameError');
        const clientEmailError = document.getElementById('clientEmailError');
        const clientPhoneError = document.getElementById('clientPhoneError');
        const clientPasswordError = document.getElementById('clientPasswordError');
        
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                let isValid = true;
                
                const name = nameField ? nameField.value.trim() : '';
                if (clientNameError) clientNameError.innerText = '';
                if (nameField) nameField.classList.remove('is-invalid');
                if (!name) {
                    if (clientNameError) clientNameError.innerText = 'Full name is required.';
                    if (nameField) nameField.classList.add('is-invalid');
                    isValid = false;
                } else if (name.length < 2) {
                    if (clientNameError) clientNameError.innerText = 'Name must be at least 2 characters.';
                    if (nameField) nameField.classList.add('is-invalid');
                    isValid = false;
                }
                
                const email = emailField ? emailField.value.trim() : '';
                if (clientEmailError) clientEmailError.innerText = '';
                if (emailField) emailField.classList.remove('is-invalid');
                if (!email) {
                    if (clientEmailError) clientEmailError.innerText = 'Email address is required.';
                    if (emailField) emailField.classList.add('is-invalid');
                    isValid = false;
                } else if (!email.includes('@') || !email.includes('.')) {
                    if (clientEmailError) clientEmailError.innerText = 'Enter a valid email address.';
                    if (emailField) emailField.classList.add('is-invalid');
                    isValid = false;
                }
                
                const phone = phoneField ? phoneField.value.trim() : '';
                if (clientPhoneError) clientPhoneError.innerText = '';
                if (phoneField) phoneField.classList.remove('is-invalid');
                if (!phone) {
                    if (clientPhoneError) clientPhoneError.innerText = 'Phone number is required.';
                    if (phoneField) phoneField.classList.add('is-invalid');
                    isValid = false;
                }

                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                const employeeIdField = document.getElementById('employee_id');
                const clientEmployeeIdError = document.getElementById('clientEmployeeIdError');
                if (selectedRole === 'agent') {
                    if (employeeIdField) employeeIdField.classList.remove('is-invalid');
                    if (clientEmployeeIdError) clientEmployeeIdError.innerText = '';
                }
                
                const password = passwordField ? passwordField.value : '';
                if (clientPasswordError) clientPasswordError.innerText = '';
                if (passwordField) passwordField.classList.remove('is-invalid');
                if (!password) {
                    if (clientPasswordError) clientPasswordError.innerText = 'Password is required.';
                    if (passwordField) passwordField.classList.add('is-invalid');
                    isValid = false;
                } else {
                    const passwordValid = validatePassword(password);
                    if (!passwordValid) {
                        if (clientPasswordError) clientPasswordError.innerText = 'Password must meet all requirements.';
                        if (passwordField) passwordField.classList.add('is-invalid');
                        isValid = false;
                    }
                }
                
                if (confirmField && !confirmField.value) {
                    const confirmError = document.getElementById('clientConfirmError');
                    if (confirmError) confirmError.innerText = 'Please confirm your password.';
                    if (confirmField) confirmField.classList.add('is-invalid');
                    isValid = false;
                } else if (confirmField && passwordField.value !== confirmField.value) {
                    const confirmError = document.getElementById('clientConfirmError');
                    if (confirmError) confirmError.innerText = 'Passwords do not match.';
                    if (confirmField) confirmField.classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                } else {
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Registering...';
                    }
                }
            });
        }
        
        // Initialize
        toggleUserFields();
        
        // ========== SOCIAL BUTTONS ==========
        const socialFacebook = document.getElementById('socialFacebookBtn');
        const socialTwitter = document.getElementById('socialTwitterBtn');
        const socialLinkedin = document.getElementById('socialLinkedinBtn');
        
        function showDemoMessage(message, type) {
            const existingAlert = document.querySelector('.auth-form .dynamic-alert');
            if(existingAlert) existingAlert.remove();
            
            const alertDiv = document.createElement('div');
            let icon = 'fa-info-circle';
            if(type === 'success') icon = 'fa-check-circle';
            if(type === 'warning') icon = 'fa-exclamation-triangle';
            
            alertDiv.className = `alert alert-${type === 'info' ? 'warning' : type} dynamic-alert`;
            alertDiv.style.cssText = 'border-radius: 8px; margin-bottom: 0.6rem; padding: 0.35rem 0.7rem; font-size: 0.65rem; font-weight: 500;';
            alertDiv.innerHTML = `<i class="fas ${icon} me-1"></i> ${message}`;
            
            const formContainer = document.querySelector('.auth-form');
            const firstElement = formContainer.firstChild;
            formContainer.insertBefore(alertDiv, firstElement);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 250);
            }, 2500);
        }
        
        if (socialFacebook) {
            socialFacebook.addEventListener('click', () => showDemoMessage('Sign up with Facebook (demo)', 'info'));
        }
        if (socialTwitter) {
            socialTwitter.addEventListener('click', () => showDemoMessage('Sign up with Twitter (demo)', 'info'));
        }
        if (socialLinkedin) {
            socialLinkedin.addEventListener('click', () => showDemoMessage('Sign up with LinkedIn (demo)', 'info'));
        }
    });
</script>
@endpush
@endsection