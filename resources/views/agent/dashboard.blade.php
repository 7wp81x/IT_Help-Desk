@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Agent Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Banner for New Agents -->
        @if(auth()->user()->approved_at && auth()->user()->approved_at->diffInDays(now()) <= 7)
        <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white rounded-xl shadow-lg p-6 mb-6 transform transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">
                        Welcome to the Team!
                    </h3>
                    <p class="text-green-100">
                        Your agent account is <strong>ACTIVE</strong>! Agent ID: <span class="font-mono font-bold text-lg">{{ auth()->user()->employee_id }}</span>
                    </p>
                    @if(auth()->user()->department)
                    <p class="text-green-100 text-sm mt-1">
                        Department: {{ auth()->user()->department }}
                    </p>
                    @endif
                </div>
                <div class="text-4xl">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards - Row 1 (Agent Focused Metrics with Links) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
            <!-- Assigned to Me Card -->
            <a href="{{ route('agent.tickets.assigned') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Assigned to Me</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['assigned_tickets']) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-person-badge text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Open / In Progress Card -->
            <a href="{{ route('agent.tickets.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Open / In Progress</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['open_assigned'] + $stats['in_progress_count']) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-clock-history text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Resolved by Me Card -->
            <a href="{{ route('agent.tickets.resolved') }}" class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Resolved by Me</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['resolved_by_me']) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-check-circle text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- My Team's Performance Card -->
            <a href="{{ route('agent.tickets.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Team Resolved</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['team_resolved'] ?? 0) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-people text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Statistics Cards - Row 2 (Additional Agent Metrics with Links) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
            <!-- Pending Approval Card -->
            <a href="{{ route('agent.tickets.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Pending Approval</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['pending_approval'] ?? 0) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-hourglass-split text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- On Hold Card -->
            <a href="{{ route('agent.tickets.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-gray-500 to-gray-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">On Hold</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['on_hold'] ?? 0) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-pause-circle text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Overdue Tickets Card -->
            <a href="{{ route('agent.tickets.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-red-500 to-red-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Overdue</p>
                            <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['overdue'] ?? 0) }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-exclamation-triangle text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Average Response Time Card -->
            <div class="group relative overflow-hidden bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-not-allowed">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
                <div class="relative p-3 md:p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Avg Response Time</p>
                            <p class="text-sm md:text-lg font-bold text-white mt-1">{{ $stats['avg_response_time'] ?? 'N/A' }}</p>
                        </div>
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="bi bi-stopwatch text-white text-base md:text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection