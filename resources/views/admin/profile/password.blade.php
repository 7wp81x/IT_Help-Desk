@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Change Password</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your account password</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Password Settings</h3>
        </div>
        
        <form action="{{ route('admin.profile.password.update') }}" method="POST" class="p-6" id="passwordForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-5">
                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password *</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 pr-10">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 toggle-password" data-target="current_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('current_password') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                    <div class="invalid-feedback text-red-500 text-xs mt-1" id="currentPasswordError"></div>
                </div>
                
                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password *</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 pr-10">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 toggle-password" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div id="passwordRequirements" class="password-reqs-line mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg" style="display: none;">
                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Password must contain:</div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <span id="req-length" class="req-badge text-xs flex items-center gap-1 text-red-500">
                                <i class="bi bi-circle-fill text-[8px]"></i> 8+ characters
                            </span>
                            <span id="req-upper" class="req-badge text-xs flex items-center gap-1 text-red-500">
                                <i class="bi bi-circle-fill text-[8px]"></i> Uppercase letter
                            </span>
                            <span id="req-lower" class="req-badge text-xs flex items-center gap-1 text-red-500">
                                <i class="bi bi-circle-fill text-[8px]"></i> Lowercase letter
                            </span>
                            <span id="req-number" class="req-badge text-xs flex items-center gap-1 text-red-500">
                                <i class="bi bi-circle-fill text-[8px]"></i> Number
                            </span>
                            <span id="req-special" class="req-badge text-xs flex items-center gap-1 text-red-500">
                                <i class="bi bi-circle-fill text-[8px]"></i> Special character
                            </span>
                        </div>
                        <div class="strength-bar h-1 rounded-full mt-2 bg-gray-200 dark:bg-gray-600 overflow-hidden">
                            <div class="strength-progress h-full w-0 transition-all duration-300 rounded-full"></div>
                        </div>
                    </div>
                    
                    @error('password') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                    <div class="invalid-feedback text-red-500 text-xs mt-1" id="newPasswordError"></div>
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password *</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 pr-10">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 toggle-password" data-target="password_confirmation">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback text-red-500 text-xs mt-1" id="confirmPasswordError"></div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.profile') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .req-badge {
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
    
    .strength-progress {
        background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== PASSWORD EYE TOGGLE ==========
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
    
    // ========== PASSWORD REQUIREMENTS ==========
    const passwordField = document.getElementById('password');
    const requirementsLine = document.getElementById('passwordRequirements');
    const confirmField = document.getElementById('password_confirmation');
    const currentPasswordField = document.getElementById('current_password');
    
    // Show requirements on focus
    if (passwordField && requirementsLine) {
        passwordField.addEventListener('focus', function() {
            requirementsLine.style.display = 'block';
        });
        
        // Hide when clicking outside
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
        const strengthBar = document.querySelector('.strength-progress');
        if (!strengthBar) return;
        let metCount = 0;
        for (const req of Object.values(requirements)) {
            if (req.met) metCount++;
        }
        const percentage = (metCount / 5) * 100;
        strengthBar.style.width = percentage + '%';
        
        // Change color based on strength
        if (percentage <= 20) {
            strengthBar.style.background = '#ef4444';
        } else if (percentage <= 60) {
            strengthBar.style.background = '#f59e0b';
        } else {
            strengthBar.style.background = '#10b981';
        }
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
        const confirmError = document.getElementById('confirmPasswordError');
        
        if (confirmField) {
            if (confirm === '') {
                if (confirmError) confirmError.innerText = '';
                confirmField.classList.remove('border-red-500', 'ring-red-500');
                return false;
            } else if (password !== confirm) {
                if (confirmError) confirmError.innerText = 'Passwords do not match.';
                confirmField.classList.add('border-red-500', 'ring-red-500');
                return false;
            } else {
                if (confirmError) confirmError.innerText = '';
                confirmField.classList.remove('border-red-500', 'ring-red-500');
                return true;
            }
        }
        return false;
    }
    
    if (confirmField) {
        confirmField.addEventListener('input', validateConfirmPassword);
    }
    
    // ========== FORM VALIDATION ==========
    const passwordForm = document.getElementById('passwordForm');
    const currentPasswordError = document.getElementById('currentPasswordError');
    const newPasswordError = document.getElementById('newPasswordError');
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate Current Password
            const currentPassword = currentPasswordField ? currentPasswordField.value : '';
            if (currentPasswordError) currentPasswordError.innerText = '';
            if (currentPasswordField) {
                currentPasswordField.classList.remove('border-red-500', 'ring-red-500');
                if (!currentPassword) {
                    if (currentPasswordError) currentPasswordError.innerText = 'Current password is required.';
                    currentPasswordField.classList.add('border-red-500', 'ring-red-500');
                    isValid = false;
                }
            }
            
            // Validate New Password
            const newPassword = passwordField ? passwordField.value : '';
            if (newPasswordError) newPasswordError.innerText = '';
            if (passwordField) {
                passwordField.classList.remove('border-red-500', 'ring-red-500');
                if (!newPassword) {
                    if (newPasswordError) newPasswordError.innerText = 'New password is required.';
                    passwordField.classList.add('border-red-500', 'ring-red-500');
                    isValid = false;
                } else {
                    const passwordValid = validatePassword(newPassword);
                    if (!passwordValid) {
                        if (newPasswordError) newPasswordError.innerText = 'Password must meet all requirements.';
                        passwordField.classList.add('border-red-500', 'ring-red-500');
                        isValid = false;
                    }
                }
            }
            
            // Validate Confirm Password
            const confirmPassword = confirmField ? confirmField.value : '';
            const confirmError = document.getElementById('confirmPasswordError');
            if (confirmField) {
                confirmField.classList.remove('border-red-500', 'ring-red-500');
                if (!confirmPassword) {
                    if (confirmError) confirmError.innerText = 'Please confirm your password.';
                    confirmField.classList.add('border-red-500', 'ring-red-500');
                    isValid = false;
                } else if (newPassword !== confirmPassword) {
                    if (confirmError) confirmError.innerText = 'Passwords do not match.';
                    confirmField.classList.add('border-red-500', 'ring-red-500');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush
@endsection