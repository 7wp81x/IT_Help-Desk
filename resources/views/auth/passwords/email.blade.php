@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="fixed inset-0 bg-gray-50 dark:bg-gray-900 overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="max-w-md w-full mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-600">
                    <div class="flex items-center justify-center">
                        <i class="bi bi-key text-white text-2xl mr-2"></i>
                        <h2 class="text-xl font-bold text-white">Reset Password</h2>
                    </div>
                    <p class="text-purple-100 text-sm text-center mt-1">Enter your email to receive reset link</p>
                </div>
                
                <div class="p-6">
                    @if (session('status'))
                        <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                            <i class="bi bi-check-circle-fill mr-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-envelope mr-1"></i> Email Address
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg transition font-medium">
                                <i class="bi bi-envelope-paper mr-2"></i> Send Password Reset Link
                            </button>
                            <a href="{{ route('login') }}" class="text-center text-sm text-gray-600 dark:text-gray-400 hover:text-purple-600 transition">
                                <i class="bi bi-arrow-left mr-1"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection