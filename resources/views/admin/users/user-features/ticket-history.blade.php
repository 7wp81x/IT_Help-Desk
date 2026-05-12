@extends('layouts.app')

@section('title', 'User Ticket History')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Ticket History</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View ticket history for selected user</p>
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
            <div class="flex items-end">
                <button onclick="loadUserTickets()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="bi bi-search"></i> View Tickets
                </button>
            </div>
        </div>
    </div>

    @if($selectedUser)
    <!-- User Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $selectedUser->name }}</h2>
                <p class="text-gray-500">{{ $selectedUser->email }}</p>
                <div class="flex gap-4 mt-2">
                    <span class="text-sm text-gray-600">Total Tickets: <strong>{{ $tickets->total() }}</strong></span>
                    <span class="text-sm text-green-600">Open: <strong>{{ $openCount }}</strong></span>
                    <span class="text-sm text-blue-600">In Progress: <strong>{{ $inProgressCount }}</strong></span>
                    <span class="text-sm text-green-600">Resolved: <strong>{{ $resolvedCount }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 dark:bg-gray-800/50">
            <h3 class="font-semibold text-gray-900">Ticket History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Closed</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->subject }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($ticket->status == 'open') bg-yellow-100 text-yellow-700
                                @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700
                                @elseif($ticket->status == 'resolved') bg-green-100 text-green-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($ticket->priority == 'urgent') bg-red-100 text-red-700
                                @elseif($ticket->priority == 'high') bg-orange-100 text-orange-700
                                @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $ticket->closed_at ? $ticket->closed_at->format('M d, Y') : '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:underline text-sm">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="bi bi-inbox text-4xl block mb-2"></i>
                            No tickets found for this user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="px-6 py-4 border-t">{{ $tickets->links() }}</div>
        @endif
    </div>
    @endif
</div>

<script>
function loadUserTickets() {
    const userId = document.getElementById('userSelect').value;
    if (userId) {
        window.location.href = '{{ url("admin/users/end-users/ticket-history") }}/' + userId;
    }
}
</script>
@endsection