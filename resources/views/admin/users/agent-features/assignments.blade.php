@extends('layouts.app')

@section('title', 'Ticket Assignments')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ticket Assignments</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage tickets assigned to you</p>
        </div>
        <div>
            <a href="{{ route('admin.users.agents') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Agents</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Assigned to You</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $assignedCount }}</p>
                </div>
                <i class="bi bi-ticket-detailed text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">In Progress</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $inProgressCount }}</p>
                </div>
                <i class="bi bi-arrow-repeat text-2xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Resolved This Week</p>
                    <p class="text-2xl font-bold text-green-600">{{ $resolvedThisWeek }}</p>
                </div>
                <i class="bi bi-check-circle text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avg Response Time</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $avgResponseTime }}</p>
                </div>
                <i class="bi bi-clock text-2xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="font-semibold text-gray-900 dark:text-white">Your Assigned Tickets</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Due Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $ticket->ticket_number ?? $ticket->id }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-sm text-blue-600 hover:underline">
                                {{ $ticket->subject }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-blue-100 text-blue-700',
                                    'medium' => 'bg-yellow-100 text-yellow-700',
                                    'high' => 'bg-orange-100 text-orange-700',
                                    'urgent' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $priorityColors[$ticket->priority] ?? 'bg-gray-100' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->user->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($ticket->due_date)
                                {{ $ticket->due_date->format('M d, Y') }}
                                @if($ticket->due_date->isPast())
                                    <span class="text-red-500 text-xs ml-1">(Overdue)</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-600 flex items-center justify-center">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.tickets.edit', $ticket) }}" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-green-100 hover:text-green-600 flex items-center justify-center">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="bi bi-inbox text-4xl text-gray-400"></i>
                                <p class="text-gray-500">No tickets assigned to you</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection