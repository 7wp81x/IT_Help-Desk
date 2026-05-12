@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reports Dashboard
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.tickets') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Ticket Report</a>
            <a href="{{ route('admin.reports.agents') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Agent Report</a>
            <a href="{{ route('admin.reports.categories') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm">Category Report</a>
        </div>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Tickets</p>
                        <p class="text-2xl font-bold">{{ $totalTickets }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="bi bi-ticket-perforated text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 flex justify-between text-xs">
                    <span class="text-green-600">Resolved: {{ $resolvedTickets }}</span>
                    <span class="text-yellow-600">Open: {{ $openTickets }}</span>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">In Progress</p>
                        <p class="text-2xl font-bold">{{ $inProgressTickets }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">This Month</p>
                        <p class="text-2xl font-bold">{{ $thisMonthTickets }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="bi bi-calendar-check text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 text-xs">
                    @php
                        $diff = $thisMonthTickets - $lastMonthTickets;
                    @endphp
                    @if($diff >= 0)
                        <span class="text-green-600">+{{ $diff }} from last month</span>
                    @else
                        <span class="text-red-600">{{ $diff }} from last month</span>
                    @endif
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">High Priority</p>
                        <p class="text-2xl font-bold text-red-600">{{ $highPriorityTickets }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Tickets by Category -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Tickets by Category</h3>
                </div>
                <div class="p-6">
                    @foreach($categories as $category)
                        @php
                            $catPercentage = $totalTickets > 0 ? ($category->tickets_count / $totalTickets) * 100 : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span>{{ $category->name }}</span>
                                <span>{{ $category->tickets_count }} tickets</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full progress-bar" data-width="{{ $catPercentage }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Agent Performance -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Agent Performance</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($agents->take(5) as $agent)
                            @php
                                $agentRate = $agent->assigned_tickets_count > 0 
                                    ? round(($agent->resolved_count ?? 0) / $agent->assigned_tickets_count * 100) 
                                    : 0;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $agent->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $agent->assigned_tickets_count }} assigned</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">{{ $agent->resolved_count ?? 0 }} resolved</p>
                                    <p class="text-xs text-gray-500">{{ $agentRate }}% rate</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <div class="border-b"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Tickets -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Recent Tickets</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Ticket #</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">User</th>
                                <th class="text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                                <tr class="border-b">
                                    <td class="py-2">{{ $ticket->ticket_number }}</td>
                                    <td>{{ Str::limit($ticket->subject, 30) }}</td>
                                    <td>
                                        @if($ticket->status == 'open')
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Open</span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">In Progress</span>
                                        @elseif($ticket->status == 'resolved')
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Resolved</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Closed</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No tickets found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection