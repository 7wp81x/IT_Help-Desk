@extends('admin.users.index')

@section('title', 'Team View')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Team View</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage complete team hierarchy</p>
        </div>
        <div>
            <a href="{{ route('admin.users.admins') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
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
                    <p class="text-sm text-gray-500">Total Members</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMembers }}</p>
                </div>
                <i class="bi bi-people text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Departments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $departments->count() }}</p>
                </div>
                <i class="bi bi-building text-2xl text-purple-500"></i>
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
                    <p class="text-sm text-gray-500">Avg Response</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $avgResponseTime }}</p>
                </div>
                <i class="bi bi-stopwatch text-2xl text-orange-500"></i>
            </div>
        </div>
    </div>

    <!-- Department-wise Team Members -->
    <div class="space-y-6">
        @foreach($departments as $department)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $department->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $department->team_members_count ?? 0 }} members</p>
                    </div>
                    <button onclick="window.location.href='{{ route('admin.departments.edit', $department) }}'" 
                            class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="bi bi-pencil"></i> Manage
                    </button>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($department->users as $member)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r 
                                @if($member->role == 'admin') from-purple-500 to-indigo-600
                                @elseif($member->role == 'agent') from-orange-500 to-red-600
                                @else from-blue-500 to-cyan-600 @endif
                                flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($member->role == 'admin')
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700">Admin</span>
                                    @elseif($member->role == 'agent')
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700">Agent</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">User</span>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ $member->position ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="flex items-center gap-1">
                                    <i class="bi bi-envelope text-xs text-gray-400"></i>
                                    <span class="text-sm text-gray-600">{{ $member->email }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs {{ $member->status == 'active' ? 'text-green-500' : 'text-gray-400' }}">
                                        <i class="bi bi-circle-fill text-xs"></i> {{ ucfirst($member->status) }}
                                    </span>
                                    <span class="text-xs text-gray-400">Joined: {{ $member->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.users.show', $member) }}" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-600 flex items-center justify-center">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $member) }}" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-green-100 hover:text-green-600 flex items-center justify-center">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection