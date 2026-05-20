@extends('layouts.app')

@section('title', 'Check Application Status')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                    <i class="bi bi-file-earmark-check text-blue-600 text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Application Status</h1>
                <p class="text-blue-100">Check the status of your agent application</p>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('application.status.check') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email address"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                        @error('email')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit"
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="bi bi-search"></i>
                        Check Status
                    </button>
                </form>

                <!-- Info Box -->
                <div class="mt-6 rounded-xl bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                        Enter the email address you used when submitting your agent application.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Need help? <a href="{{ route('welcome') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Contact support</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
