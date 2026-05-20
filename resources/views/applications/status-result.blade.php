@extends('layouts.app')

@section('title', 'Application Status Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('application.status.form') }}" class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header with Status -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            {{ $application->full_name }}
                        </h1>
                        <p class="text-blue-100">Application ID: #{{ $application->id }}</p>
                    </div>
                    @php
                        $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                        $statusIcon = 'bi-clock-history';
                        $statusText = 'Pending Review';
                        
                        if ($application->status === 'approved') {
                            $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                            $statusIcon = 'bi-check-circle-fill';
                            $statusText = 'Approved';
                        } elseif ($application->status === 'rejected') {
                            $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                            $statusIcon = 'bi-x-circle-fill';
                            $statusText = 'Rejected';
                        }
                    @endphp
                    <div class="text-right">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $statusClass }} font-semibold">
                            <i class="bi {{ $statusIcon }}"></i>
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <!-- Submitted Date -->
                <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Submitted On</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $application->created_at->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Time Since Submission</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $application->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-person-badge text-blue-600 dark:text-blue-400"></i>
                        Contact Information
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                            <p class="font-semibold text-gray-900 dark:text-white break-all">
                                {{ $application->email }}
                            </p>
                        </div>
                        @if($application->phone)
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Phone</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $application->phone }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Certifications -->
                @php
                    $certs = $application->certifications_list ? explode(',', $application->certifications_list) : [];
                @endphp
                @if(count($certs) > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-award text-blue-600 dark:text-blue-400"></i>
                        Certifications
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($certs as $cert)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            <i class="bi bi-check-circle mr-1"></i>
                            {{ trim($cert) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Status Timeline -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-timeline text-blue-600 dark:text-blue-400"></i>
                        Status Timeline
                    </h2>
                    <div class="space-y-4">
                        <!-- Submitted -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <div class="w-0.5 h-12 bg-gray-300 dark:bg-gray-600 my-2"></div>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Application Submitted</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        <!-- Under Review -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                @if($application->status === 'pending')
                                <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-white animate-pulse">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                @else
                                <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center text-white">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                @endif
                                <div class="w-0.5 h-12 {{ $application->status === 'pending' ? 'bg-gray-300 dark:bg-gray-600' : 'bg-blue-600' }} my-2"></div>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Under Review</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Our team is reviewing your application</p>
                            </div>
                        </div>

                        <!-- Approved/Rejected -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                @if($application->status === 'approved')
                                <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                @elseif($application->status === 'rejected')
                                <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white">
                                    <i class="bi bi-x-lg"></i>
                                </div>
                                @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-400">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                @endif
                            </div>
                            <div>
                                @if($application->status === 'approved')
                                <p class="font-semibold text-green-700 dark:text-green-400">✓ Application Approved</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Congratulations! Your agent account has been activated.</p>
                                @if($application->user)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Agent ID: <span class="font-mono font-semibold">{{ $application->user->employee_id }}</span>
                                </p>
                                @endif
                                @elseif($application->status === 'rejected')
                                <p class="font-semibold text-red-700 dark:text-red-400">✗ Application Rejected</p>
                                @if($application->admin_notes)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    <strong>Reason:</strong> {{ $application->admin_notes }}
                                </p>
                                @else
                                <p class="text-sm text-gray-600 dark:text-gray-400">We were unable to move forward with your application at this time.</p>
                                @endif
                                @else
                                <p class="font-semibold text-gray-900 dark:text-white">Awaiting Decision</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">We're still reviewing your application. You'll be notified soon.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($application->status === 'approved' && $application->user)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                    <p class="text-green-900 dark:text-green-200 mb-4">
                        <strong>Your account is ready!</strong> You can now log in and start handling customer tickets.
                    </p>
                    <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login to Dashboard
                    </a>
                </div>
                @elseif($application->status === 'pending')
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                    <p class="text-yellow-900 dark:text-yellow-200 mb-2">
                        <i class="bi bi-info-circle mr-2"></i>
                        <strong>Your application is under review.</strong>
                    </p>
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        We'll send you an email notification as soon as we've made a decision. Thank you for your patience!
                    </p>
                </div>
                @elseif($application->status === 'rejected')
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                    <p class="text-red-900 dark:text-red-200 mb-2">
                        <i class="bi bi-exclamation-circle mr-2"></i>
                        <strong>Application Status: Rejected</strong>
                    </p>
                    <p class="text-sm text-red-800 dark:text-red-300">
                        We appreciate your interest. Please check your email for detailed feedback.
                    </p>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                    <i class="bi bi-info-circle mr-2"></i>
                    Need help? Contact our support team at support@ithelpdesk.com
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
