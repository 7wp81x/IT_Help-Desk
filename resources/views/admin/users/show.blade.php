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
            @if($user->role !== 'admin')
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors shadow-sm">
                <i class="bi bi-pencil-square"></i>
                <span>Edit User</span>
            </a>
            @endif
            

               <!-- Dynamic back button based on user role -->
    @if($user->role === 'agent')
    <a href="{{ route('admin.users.agents') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors shadow-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Agents</span>
    </a>
    @elseif($user->role === 'user')
    <a href="{{ route('admin.users.end-users') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors shadow-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back to End Users</span>
    </a>
    @else
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors shadow-sm">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Users</span>
    </a>
    @endif

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                <!-- Profile Header -->
                <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30">
                    <div class="w-28 h-28 mx-auto rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg ring-4 ring-white dark:ring-gray-800">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        @if($user->role == 'admin') 
                            <i class="bi bi-shield-lock me-1"></i> Administrator
                        @elseif($user->role == 'agent') 
                            <i class="bi bi-headset me-1"></i> Support Agent
                        @else 
                            <i class="bi bi-person me-1"></i> End User
                        @endif
                    </p>
                    <div class="mt-3">
                        @if($user->status == 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <i class="bi bi-check-circle-fill text-xs"></i>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                <i class="bi bi-x-circle-fill text-xs"></i>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">
                        <i class="bi bi-info-circle mr-2"></i> Contact Information
                    </h3>
                    <div class="space-y-3">
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
                    </div>
                </div>
                
             
                
                <!-- Employment Information (only for agents/admins) -->
@if($user->department || $user->position || $user->employee_id)
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">
        <i class="bi bi-briefcase mr-2"></i> Employment Information
    </h3>
    <div class="space-y-3">
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
        @if($user->employee_id)
        <div class="flex items-center gap-3 text-sm">
            <i class="bi bi-card-text text-gray-400 w-5"></i>
            <span class="text-gray-600 dark:text-gray-400 font-mono text-xs">ID: {{ $user->employee_id }}</span>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Account Information (for all users) -->
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">
        <i class="bi bi-calendar mr-2"></i> Account Information
    </h3>
    <div class="space-y-3">
        <div class="flex items-center gap-3 text-sm">
            <i class="bi bi-calendar text-gray-400 w-5"></i>
            <span class="text-gray-600 dark:text-gray-400">Joined: {{ $user->created_at->format('F d, Y') }}</span>
        </div>
        @if($user->last_login_at)
        <div class="flex items-center gap-3 text-sm">
            <i class="bi bi-clock-history text-gray-400 w-5"></i>
            <span class="text-gray-600 dark:text-gray-400">Last Login: {{ $user->last_login_at->diffForHumans() }}</span>
        </div>
        @endif
    </div>
</div>
                <!-- Categories Section (for agents) -->
                @if($user->role === 'agent' && isset($user->categories) && $user->categories->count() > 0)
                <div class="p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">
                        <i class="bi bi-tags mr-2"></i> Specialized Categories
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->categories as $category)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                            <i class="bi bi-tag text-xs"></i>
                            {{ $category->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- User Stats and Tickets -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Tickets</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_tickets'] }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="bi bi-ticket-detailed text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Open Tickets</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['open_tickets'] }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                            <i class="bi bi-envelope-open text-yellow-600 dark:text-yellow-400 text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Resolved</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['resolved_tickets'] }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Closed</p>
                            <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $stats['closed_tickets'] }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <i class="bi bi-archive text-gray-600 dark:text-gray-400 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Skills Section -->
            @if($user->skills)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <i class="bi bi-star mr-2"></i> Skills & Expertise
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $user->skills) as $skill)
                            @if(trim($skill))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                <i class="bi bi-check-circle-fill text-green-500 text-xs"></i>
                                {{ trim($skill) }}
                            </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Recent Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <i class="bi bi-clock-history mr-2"></i> Recent Tickets
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-3 text-sm font-mono text-gray-900 dark:text-white">#{{ $ticket->ticket_number ?? $ticket->id }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                                        {{ Str::limit($ticket->subject, 50) }}
                                    </a>
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $statusColors = [
                                            'open' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'assigned' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'resolved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'closed' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                            'canceled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                        $statusColor = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                        <i class="bi 
                                            @if($ticket->status == 'open') bi-envelope-open
                                            @elseif($ticket->status == 'in_progress') bi-arrow-repeat
                                            @elseif($ticket->status == 'resolved') bi-check-circle
                                            @elseif($ticket->status == 'closed') bi-archive
                                            @elseif($ticket->status == 'canceled') bi-x-circle
                                            @else bi-question-circle
                                            @endif text-xs"></i>
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $priorityColors = [
                                            'low' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            'medium' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'urgent' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                        $priorityColor = $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full {{ $priorityColor }}">
                                        <i class="bi 
                                            @if($ticket->priority == 'low') bi-arrow-down-circle
                                            @elseif($ticket->priority == 'medium') bi-dash-circle
                                            @elseif($ticket->priority == 'high') bi-arrow-up-circle
                                            @elseif($ticket->priority == 'urgent') bi-exclamation-triangle
                                            @else bi-circle
                                            @endif text-xs"></i>
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <i class="bi bi-ticket text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">No tickets found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($recentTickets->count() > 0)
                <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <a href="{{ route('admin.tickets.all', $user) }}?tab=tickets" class="text-sm text-blue-600 hover:underline dark:text-blue-400">View all tickets →</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

