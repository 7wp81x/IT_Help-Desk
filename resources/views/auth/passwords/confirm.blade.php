@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-yellow-500 to-orange-500">
                <div class="flex items-center justify-center">
                    <i class="bi bi-shield-check text-white text-2xl mr-2"></i>
                    <h2 class="text-xl font-bold text-white">Confirm Password</h2>
                </div>
                <p class="text-yellow-100 text-sm text-center mt-1">
                    Please verify your password to continue
                </p>
            </div>
            
            <div class="p-6">
                <div class="mb-4 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                    <i class="bi bi-shield-exclamation mr-2"></i>
                    {{ __('Please confirm your password before continuing.') }}
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-lock mr-1"></i> Password
                        </label>
                        <input type="password" name="password" required autocomplete="current-password"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-lg transition font-medium">
                            <i class="bi bi-check-circle mr-2"></i> Confirm Password
                        </button>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-center text-sm text-gray-600 dark:text-gray-400 hover:text-yellow-600 transition">
                                <i class="bi bi-question-circle mr-1"></i> Forgot Your Password?
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection