@extends('layouts.app')

@section('title', 'Available Support Agents')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Available Support Agents</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View support agents available to help</p>
        </div>
        <div>
            <a href="{{ route('admin.users.end-users') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>
    </div>

    <!-- Agents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($availableAgents as $agent)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition">
            <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700 bg-gradient-to-br from-orange-50 to-white dark:from-gray-800 dark:to-gray-800">
                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                </div>
                <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $agent->name }}</h3>
                <p class="text-sm text-gray-500">{{ $agent->position ?? 'Support Agent' }}</p>
                <div class="mt-2 inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700">
                    <i class="bi bi-circle-fill text-xs"></i>
                    <span class="text-sm font-medium">Available Now</span>
                </div>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Specialization:</span>
                    <span class="font-medium text-gray-900">{{ $agent->specialization ?? 'General Support' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Experience:</span>
                    <span class="font-medium text-gray-900">{{ $agent->experience ?? '3+ years' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Languages:</span>
                    <span class="font-medium text-gray-900">{{ $agent->languages ?? 'English' }}</span>
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
                <button onclick="createTicketWithAgent('{{ $agent->id }}')" 
                        class="w-full mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center justify-center gap-2">
                    <i class="bi bi-chat-dots"></i>
                    Create Ticket with {{ explode(' ', $agent->name)[0] }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

    @if($offlineAgents->count() > 0)
    <!-- Offline Agents -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Offline Agents</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($offlineAgents as $agent)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-4 opacity-60">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-gray-400 to-gray-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $agent->name }}</p>
                        <p class="text-xs text-gray-500">Last seen: {{ $agent->last_active_at ? $agent->last_active_at->diffForHumans() : 'Unknown' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function createTicketWithAgent(agentId) {
    window.location.href = '{{ route("admin.tickets.create") }}?agent_id=' + agentId;
}
</script>
@endsection