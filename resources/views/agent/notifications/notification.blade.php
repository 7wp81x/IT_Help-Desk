@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Stay updated with your ticket activity</p>
            </div>
            <div class="flex items-center gap-4">
                @if($unreadCount > 0)
                <form method="POST" action="{{ route('agent.notifications.mark-all-read') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">
                        Mark all as read
                    </button>
                </form>
                @endif
                @if($readCount > 0)
                <form method="POST" action="{{ route('agent.notifications.delete-all') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 transition-colors">
                        Clear all
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-2">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data ?? [];
                $type = $notification->type ?? '';
                $read = $notification->read_at !== null;
                $icon = 'bi-bell';
                $iconBg = 'bg-gray-100 dark:bg-gray-800';
                $iconColor = 'text-gray-500';
                $actionUrl = route('agent.notifications.show', $notification->id);
                
                if (str_contains($type, 'Ticket') || str_contains($type, 'ticket')) {
                    $ticketId = data_get($data, 'ticket_id');
                    if ($ticketId) {
                        $actionUrl = route('agent.tickets.show', $ticketId);
                    }
                    $icon = 'bi-ticket';
                    $iconBg = 'bg-blue-100 dark:bg-blue-900/30';
                    $iconColor = 'text-blue-600 dark:text-blue-400';
                } elseif (str_contains($type, 'Comment') || str_contains($type, 'Reply')) {
                    $ticketId = data_get($data, 'ticket_id');
                    if ($ticketId) {
                        $actionUrl = route('agent.tickets.show', $ticketId);
                    }
                    $icon = 'bi-chat';
                    $iconBg = 'bg-green-100 dark:bg-green-900/30';
                    $iconColor = 'text-green-600 dark:text-green-400';
                } elseif (str_contains($type, 'Assignment')) {
                    $ticketId = data_get($data, 'ticket_id');
                    if ($ticketId) {
                        $actionUrl = route('agent.tickets.show', $ticketId);
                    }
                    $icon = 'bi-person-check';
                    $iconBg = 'bg-purple-100 dark:bg-purple-900/30';
                    $iconColor = 'text-purple-600 dark:text-purple-400';
                }
                
                $title = data_get($data, 'title', 'Notification');
                $message = data_get($data, 'message', '');
                $time = $notification->created_at->diffForHumans();
            @endphp
            
            <div class="group relative">
                <div class="absolute -left-0.5 top-4 w-1.5 h-1.5 rounded-full {{ $read ? 'bg-gray-300 dark:bg-gray-600' : 'bg-blue-500' }}"></div>
                <a href="{{ $actionUrl }}" class="block rounded-xl transition-all duration-200 {{ $read ? 'hover:bg-gray-50 dark:hover:bg-gray-800/50' : 'bg-blue-50/50 dark:bg-blue-900/20 hover:bg-blue-50 dark:hover:bg-blue-900/30' }}">
                    <div class="flex gap-4 p-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center">
                                <i class="{{ $icon }} {{ $iconColor }} text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $message }}</p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $time }}</p>
                                    @if(!$read)
                                        <span class="inline-block mt-1 w-2 h-2 rounded-full bg-blue-500"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <i class="bi bi-bell-slash text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No notifications yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">When you receive notifications, they'll appear here</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection