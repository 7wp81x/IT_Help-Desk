@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket Report
        </h2>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">Back to Reports</a>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border rounded-lg px-3 py-2">
                            <option value="">All</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full border rounded-lg px-3 py-2">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button>
                        <a href="{{ route('admin.reports.tickets') }}" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded-lg">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tickets Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Ticket #</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Priority</th>
                                <th class="text-left">Category</th>
                                <th class="text-left">User</th>
                                <th class="text-left">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-b">
                                    <td class="py-2">{{ $ticket->ticket_number }}</td>
                                    <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                    <td>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($ticket->status == 'open') bg-yellow-100 text-yellow-800
                                            @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($ticket->priority >= 4) bg-red-100 text-red-800
                                            @elseif($ticket->priority >= 2) bg-orange-100 text-orange-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            Priority {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->category->name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                                    <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No tickets found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection