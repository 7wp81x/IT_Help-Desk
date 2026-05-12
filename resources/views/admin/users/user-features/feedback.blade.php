@extends('layouts.app')

@section('title', 'User Feedback')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Feedback & Ratings</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View feedback and ratings submitted by users</p>
        </div>
        <div>
            <a href="{{ route('admin.users.end-users') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Ratings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRatings }}</p>
                </div>
                <i class="bi bi-star text-2xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Average Rating</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($avgRating, 1) }}</p>
                </div>
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                    @endfor
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Positive Feedback</p>
                    <p class="text-2xl font-bold text-green-600">{{ $positiveCount }}%</p>
                </div>
                <i class="bi bi-emoji-smile text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Needs Improvement</p>
                    <p class="text-2xl font-bold text-red-600">{{ $negativeCount }}%</p>
                </div>
                <i class="bi bi-emoji-frown text-2xl text-red-500"></i>
            </div>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 dark:bg-gray-800/50">
            <h3 class="font-semibold text-gray-900">Recent Feedback</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($feedbacks as $feedback)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($feedback->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $feedback->user->name ?? 'Anonymous' }}</p>
                            <p class="text-xs text-gray-500">{{ $feedback->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill text-{{ $i <= $feedback->rating ? 'yellow' : 'gray' }}-400 text-sm"></i>
                        @endfor
                    </div>
                </div>
                <div class="ml-13">
                    <p class="text-gray-700 dark:text-gray-300">{{ $feedback->comment }}</p>
                    @if($feedback->ticket_id)
                    <div class="mt-2">
                        <a href="{{ route('admin.tickets.show', $feedback->ticket_id) }}" class="text-sm text-blue-600 hover:underline">
                            <i class="bi bi-ticket-detailed"></i> View related ticket
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="bi bi-chat-dots text-4xl text-gray-400 block mb-2"></i>
                <p class="text-gray-500">No feedback available yet</p>
            </div>
            @endforelse
        </div>
        @if($feedbacks->hasPages())
        <div class="px-6 py-4 border-t">{{ $feedbacks->links() }}</div>
        @endif
    </div>
</div>
@endsection