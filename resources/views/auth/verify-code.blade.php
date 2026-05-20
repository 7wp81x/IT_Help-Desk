@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transform transition duration-300 scale-100 md:scale-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-envelope-check text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Verify Your Email</h2>
            <p class="text-blue-100">Enter the 6-digit code sent to your email</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4 text-sm text-green-700 dark:text-green-400">
                    <i class="bi bi-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 text-sm text-blue-700 dark:text-blue-400">
                    <i class="bi bi-info-circle mr-2"></i>
                    {{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify-code') }}">
                @csrf

                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Verification Code
                    </label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code" 
                        maxlength="6" 
                        inputmode="numeric"
                        placeholder="000000"
                        class="w-full px-4 py-3 text-center text-2xl tracking-widest border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition"
                        value="{{ old('code') }}"
                        required
                        autofocus
                    >
                    @error('code')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="bi bi-check-lg"></i>
                        Verify
                    </button>
                </div>
            </form>

            <!-- Resend Code Section -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-4">
                    Didn't receive the code?
                </p>
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        Request New Code
                    </button>
                </form>
            </div>

            <!-- Info Message -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <p class="text-xs text-blue-700 dark:text-blue-400">
                    <i class="bi bi-info-circle mr-2"></i>
                    The code will expire in <strong>30 minutes</strong>. Make sure to verify your email to unlock all features.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-format the code input to only accept digits
    document.getElementById('code').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });

    // Auto-submit when 6 digits are entered
    document.getElementById('code').addEventListener('input', function(e) {
        if (this.value.length === 6) {
            // Optional: auto-submit after a short delay
            // setTimeout(() => this.form.submit(), 300);
        }
    });
</script>
@endsection
