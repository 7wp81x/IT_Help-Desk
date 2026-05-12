@extends('layouts.app')

@section('title', 'User Activity Log')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Activity Log</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track user login history and activity</p>
        </div>
        <div>
            <a href="{{ route('admin.users.end-users') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>
    </div>

    <!-- User Selector -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                <select id="userSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white dark:bg-gray-700">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $selectedUser && $selectedUser->id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button onclick="loadUserActivity()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="bi bi-search"></i> View Activity
                </button>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    @if($selectedUser)
    <!-- User Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900">{{ $selectedUser->name }}</h3>
                <p class="text-sm text-gray-500">{{ $selectedUser->email }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Last Login</p>
                <p class="font-semibold text-gray-900">{{ $selectedUser->last_login_at ? $selectedUser->last_login_at->diffForHumans() : 'Never' }}</p>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 dark:bg-gray-800/50">
            <h3 class="font-semibold text-gray-900">Activity Timeline</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($activities as $activity)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full 
                            @if($activity->type == 'login') bg-green-100 
                            @elseif($activity->type == 'ticket_created') bg-blue-100 
                            @elseif($activity->type == 'ticket_updated') bg-yellow-100 
                            @else bg-gray-100 @endif
                            flex items-center justify-center">
                            <i class="bi 
                                @if($activity->type == 'login') bi-box-arrow-in-right text-green-600
                                @elseif($activity->type == 'ticket_created') bi-plus-circle text-blue-600
                                @elseif($activity->type == 'ticket_updated') bi-pencil text-yellow-600
                                @else bi-info-circle text-gray-600 @endif
                            "></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $activity->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                        @if($activity->details)
                        <p class="text-xs text-gray-400 mt-1">{{ json_encode($activity->details) }}</p>
                        @endif
                        @if($activity->ip_address)
                        <p class="text-xs text-gray-400 mt-1">IP: {{ $activity->ip_address }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="bi bi-activity text-4xl text-gray-400 block mb-2"></i>
                <p class="text-gray-500">No activity recorded for this user</p>
            </div>
            @endforelse
        </div>
        @if($activities->hasPages())
        <div class="px-6 py-4 border-t">{{ $activities->links() }}</div>
        @endif
    </div>
    @endif
</div>

<script>
function loadUserActivity() {
    const userId = document.getElementById('userSelect').value;
    if (userId) {
        window.location.href = '{{ url("admin/users/end-users/activity") }}/' + userId;
    }
}
</script>
@endsection