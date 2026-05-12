@extends('layouts.app')

@section('title', 'Support Team')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Support Team</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View other support agents (read-only)</p>
        </div>
        <div>
            <a href="{{ route('admin.users.agents') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Team Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Agents</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAgents }}</p>
                </div>
                <i class="bi bi-people text-2xl text-orange-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Online Now</p>
                    <p class="text-2xl font-bold text-green-600">{{ $onlineCount }}</p>
                </div>
                <i class="bi bi-wifi text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avg Rating</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($avgTeamRating, 1) }}</p>
                </div>
                <i class="bi bi-star text-2xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $totalResolved }}</p>
                </div>
                <i class="bi bi-check2-circle text-2xl text-green-500"></i>
            </div>
        </div>
    </div>

    <!-- Agents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($agents as $agent)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            <div class="p-6 text-center border-b border-gray-200">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                </div>
                <h3 class="mt-3 text-lg font-semibold text-gray-900 dark:text-white">{{ $agent->name }}</h3>
                <p class="text-sm text-gray-500">{{ $agent->position ?? 'Support Agent' }}</p>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full {{ $agent->is_online ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        <i class="bi bi-circle-fill text-xs"></i>
                        {{ $agent->is_online ? 'Online' : 'Offline' }}
                    </span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Specialization:</span>
                    <span class="font-medium text-gray-900">{{ $agent->specialization ?? 'General Support' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Tickets Resolved:</span>
                    <span class="font-medium text-gray-900">{{ $agent->resolved_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Rating:</span>
                    <div class="flex items-center gap-1">
                        <span class="font-medium text-gray-900">{{ number_format($agent->rating ?? 4.5, 1) }}</span>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Department:</span>
                    <span class="font-medium text-gray-900">{{ $agent->department ?? 'Support' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Joined:</span>
                    <span class="font-medium text-gray-900">{{ $agent->created_at->format('M Y') }}</span>
                </div>
            </div>
            <!-- NO EDIT/DELETE BUTTONS - VIEW ONLY -->
        </div>
        @endforeach
    </div>
</div>
@endsection