@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Details</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View complete user information</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i class="bi bi-pencil-square"></i>
                <span>Edit User</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if($user->role == 'admin') Administrator
                        @elseif($user->role == 'agent') Support Agent
                        @else End User
                        @endif
                    </p>
                    <div class="mt-2">
                        @if($user->status == 'active')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center gap-3 text-sm">
                        <i class="bi bi-envelope text-gray-400 w-5"></i>
                        <span class="text-gray-600 dark:text-gray-400">{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                    <div class="flex items-center gap-3 text-sm">
                        <i class="bi bi-telephone text-gray-400 w-5"></i>
                        <span class="text-gray-600 dark:text-gray-400">{{ $user->phone }}</span>
                    </div>
                    @endif
                    @if($user->employee_id)
                    <div class="flex items-center gap-3 text-sm">
                        <i class="bi bi-card-text text-gray-400 w-5"></i>
                        <span class="text-gray-600 dark:text-gray-400">ID: {{ $user->employee_id }}</span>
                    </div>
                    @endif
                    @if($user->department)
                    <div class="flex items-center gap-3 text-sm">
                        <i class="bi bi-building text-gray-400 w-5"></i>
                        <span class="text-gray-600 dark:text-gray-400">{{ $user->department }}</span>
                    </div>
                    @endif
                    @if($user->position)
                    <div class="flex items-center gap-3 text-sm">
                        <i class="bi bi-briefcase text-gray-400 w-5"></i>
                        <span class="text-gray-600 dark:text-gray-400">{{ $user->position }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-3 text-sm">
                        <i class="bi bi-calendar text-gray-400 w-5"></i>
                        <span class="text-gray-600 dark:text-gray-400">Joined: {{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Stats and Tickets -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_tickets'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Open Tickets</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['open_tickets'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['resolved_tickets'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Closed</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['closed_tickets'] }}</p>
                </div>
            </div>
            
            <!-- Recent Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Tickets</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Ticket #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">#{{ $ticket->ticket_number ?? $ticket->id }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-sm text-blue-600 hover:underline">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($ticket->status == 'open') bg-yellow-100 text-yellow-700
                                        @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700
                                        @elseif($ticket->status == 'resolved') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No tickets found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Skills & Specialization -->
            @if($user->specialization || $user->skills)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                @if($user->specialization)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Specialization</h4>
                    <p class="text-gray-900 dark:text-white">{{ $user->specialization }}</p>
                </div>
                @endif
                @if($user->skills)
                <div>
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Skills</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $user->skills) as $skill)
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">{{ trim($skill) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection