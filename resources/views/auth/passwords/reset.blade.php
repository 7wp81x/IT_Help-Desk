@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="auth-header text-center mb-4">
    <h3 class="font-bold text-xl" style="color: var(--text-primary)">Reset Your Password</h3>
    <p style="color: var(--text-secondary)">Set a new password for your account.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email Field -->
    <div class="mb-4">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus 
               class="form-control w-100" 
               placeholder="you@example.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- New Password Field with Eye Icon INSIDE -->
    <div class="mb-4">
        <label for="password" class="form-label">New Password</label>
        <div class="password-wrapper">
            <input id="password" type="password" name="password" required 
                   class="form-control w-100" 
                   placeholder="Enter new password">
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password Field with Eye Icon INSIDE -->
    <div class="mb-4">
        <label for="password-confirm" class="form-label">Confirm Password</label>
        <div class="password-wrapper">
            <input id="password-confirm" type="password" name="password_confirmation" required 
                   class="form-control w-100" 
                   placeholder="Repeat new password">
            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-primary w-100 py-2 px-4">
        Reset Password
    </button>
</form>

@push('scripts')
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection