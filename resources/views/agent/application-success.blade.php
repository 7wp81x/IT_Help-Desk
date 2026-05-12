@extends('layouts.app')

@section('title', 'Application Submitted')

@section('content')
@push('styles')
    <style>
        .main-content { margin-left: 0 !important; }
    </style>
@endpush
<div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-10 text-center">
        <div class="mx-auto inline-flex h-24 w-24 items-center justify-center rounded-full bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-200">
            <i class="fas fa-check fa-2x"></i>
        </div>
        <h1 class="mt-6 text-3xl font-semibold text-gray-900 dark:text-gray-100">Application Received</h1>
        <p class="mt-3 text-gray-600 dark:text-gray-300">Thank you for applying. Our team will review your submission and email you next steps shortly. If you want to register now, use the same email address as your application.</p>

        <div class="mt-8 grid gap-3 sm:grid-cols-3">
            <a href="{{ route('agent.apply') }}" class="inline-flex items-center justify-center rounded-full border border-blue-600 bg-transparent px-6 py-3 text-sm font-semibold text-blue-700 transition duration-150 hover:bg-blue-50 dark:border-blue-500 dark:text-blue-300 dark:hover:bg-blue-900/60">
                <i class="fas fa-arrow-left mr-2"></i>Submit another application
            </a>
            <a href="{{ route('register', ['role' => 'agent']) }}" class="inline-flex items-center justify-center rounded-full border border-green-600 bg-green-50 px-6 py-3 text-sm font-semibold text-green-700 transition duration-150 hover:bg-green-100 dark:border-green-500 dark:bg-green-900/40 dark:text-green-200 dark:hover:bg-green-900">
                <i class="fas fa-user-plus mr-2"></i>Register as Agent
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-sm font-semibold text-white transition duration-150 hover:from-blue-700 hover:to-blue-800">
                <i class="fas fa-user-check mr-2"></i>Return to Login
            </a>
        </div>
    </div>
</div>
@endsection