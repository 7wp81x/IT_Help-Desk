@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $agent->name }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Agent Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white text-center">
                        <div class="w-20 h-20 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center text-4xl font-bold mb-4">
                            {{ strtoupper(substr($agent->name, 0, 1)) }}
                        </div>
                        <h3 class="text-2xl font-bold">{{ $agent->name }}</h3>
                        <p class="text-blue-100">{{ $agent->department ?? 'Support Team' }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="p-6 space-y-4">
                        <!-- Average Rating -->
                        <div class="border-b pb-4">
                            <p class="text-sm text-gray-600 mb-2">Average Rating</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= floor($stats['average_rating']) ? '-fill' : '' }} text-yellow-400"></i>
                                    @endfor
                                </div>
                                <span class="text-2xl font-bold text-yellow-500">{{ number_format($stats['average_rating'], 1) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">{{ $stats['total_ratings'] }} ratings</p>
                        </div>

                        <!-- Stats -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-600">Tickets Resolved</span>
                                <span class="text-lg font-bold text-blue-600">{{ $stats['total_resolved'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Avg Response Time</span>
                                <span class="text-lg font-bold text-green-600">~{{ $stats['avg_response_time'] ?? 2 }} hours</span>
                            </div>
                        </div>

                        <!-- Email Contact -->
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Email</p>
                            <p class="text-sm font-medium text-blue-600 truncate">{{ $agent->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="bg-white rounded-xl shadow p-6 mt-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Need Help?</h4>
                    <p class="text-sm text-gray-600 mb-4">Create a new ticket to get help from this agent.</p>
                    <a href="{{ route('user.tickets.create') }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                        Create Ticket
                    </a>
                </div>
            </div>

            <!-- Agent Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Ratings -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Ratings & Reviews</h4>
                    
                    @if($agent->agentRatings->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($agent->agentRatings as $rating)
                                <div class="border-b pb-4 last:border-b-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $rating->user->name ?? 'Anonymous User' }}</p>
                                            <p class="text-xs text-gray-500">{{ $rating->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }} text-yellow-400"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($rating->comment)
                                        <p class="text-sm text-gray-600">{{ $rating->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No ratings yet</p>
                    @endif
                </div>

                <!-- Your Tickets with this Agent -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Your Tickets with {{ $agent->name }}</h4>
                    
                    @if($userTicketsWithAgent->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($userTicketsWithAgent as $ticket)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <a href="{{ route('user.tickets.show', $ticket) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                                {{ $ticket->ticket_number }}
                                            </a>
                                            <p class="text-sm text-gray-600">{{ $ticket->subject }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                                   @if($ticket->status === 'open') bg-blue-100 text-blue-800
                                                   @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                   @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                                   @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">Created {{ $ticket->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No tickets with this agent yet</p>
                    @endif
                </div>

                <!-- Specializations -->
                @if($agent->specialization)
                    <div class="bg-white rounded-xl shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Specializations</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $agent->specialization) as $spec)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                    {{ trim($spec) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
