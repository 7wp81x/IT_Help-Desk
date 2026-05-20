@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-header">
        <h3>Forgot your password?</h3>
        <p>Enter your email address or phone number and we'll send a secure password reset link.</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus class="form-control" placeholder="you@example.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+1 555 555 5555">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <p class="text-muted small mb-3">Provide either your email address or phone number. Leave the other field blank.</p>

        <button type="submit" class="btn-primary w-100">Find My Account</button>
    </form>

    <div class="mt-4 text-center">
        <p class="mb-2">Remembered your password?</p>
        <a href="{{ route('login') }}">Return to login</a>
    </div>
@endsection
