@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Agent Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <!-- Welcome Banner for New Agents -->
    @if(auth()->user()->approved_at && auth()->user()->approved_at->diffInDays(now()) <= 7)
    <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">
                    🎉 Welcome to the Team!
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

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="bi bi-ticket-perforated text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Assigned to Me</p>
                        <p class="text-2xl font-bold">{{ $stats['assigned_tickets'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Open/In Progress</p>
                        <p class="text-2xl font-bold">{{ $stats['open_assigned'] + $stats['in_progress_count'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Resolved by Me</p>
                        <p class="text-2xl font-bold">{{ $stats['resolved_by_me'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">My Assigned Tickets</h3>
            </div>
            <div class="p-6">
                @forelse($myTickets as $ticket)
                    <div class="border-b py-2">
                        <p><strong>{{ $ticket->ticket_number }}</strong> - {{ $ticket->subject }}</p>
                        <p class="text-sm text-gray-500">Status: {{ $ticket->status }} | Priority: {{ $ticket->priority }}</p>
                    </div>
                @empty
                    <p>No tickets assigned.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection