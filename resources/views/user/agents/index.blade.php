@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Our Support Agents
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter & Search</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Agent</label>
                    <input type="text" name="search" placeholder="Agent name..." 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Departments</option>
                        <option value="IT" {{ request('department') === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="HR" {{ request('department') === 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="Finance" {{ request('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="rating_desc" {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>Highest Rating</option>
                        <option value="rating_asc" {{ request('sort') === 'rating_asc' ? 'selected' : '' }}>Lowest Rating</option>
                        <option value="resolved_desc" {{ request('sort') === 'resolved_desc' ? 'selected' : '' }}>Most Resolved</option>
                        <option value="response_time" {{ request('sort') === 'response_time' ? 'selected' : '' }}>Fastest Response</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Agents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($agents as $agent)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                    <!-- Agent Header with Avatar -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-3xl font-bold">
                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-bold">{{ $agent->name }}</h4>
                                <p class="text-blue-100 text-sm">{{ $agent->department ?? 'General Support' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Stats -->
                    <div class="p-6 border-b border-gray-200">
                        <!-- Rating -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600">Average Rating</span>
                                <span class="text-2xl font-bold text-yellow-500">{{ number_format($agent->average_rating, 1) }}/5</span>
                            </div>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= floor($agent->average_rating) ? '-fill' : '' }} text-yellow-400"></i>
                                @endfor
                                <span class="text-xs text-gray-500 ml-2">({{ $agent->total_ratings }} ratings)</span>
                            </div>
                        </div>

                        <!-- Stats Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600">Resolved Tickets</p>
                                <p class="text-xl font-bold text-blue-600">{{ $agent->total_resolved }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600">Response Time</p>
                                <p class="text-sm font-bold text-green-600">~{{ $agent->avg_response_time ?? 2 }}h avg</p>
                            </div>
                        </div>
                    </div>

                    <!-- View Profile Button -->
                    <div class="p-4">
                        <a href="{{ route('user.agents.show', $agent) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            View Profile
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl shadow p-12 text-center">
                    <i class="bi bi-person-slash text-4xl text-gray-300 block mb-4"></i>
                    <p class="text-gray-500 text-lg">No agents found matching your criteria.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($agents->hasPages())
            <div class="mt-8">
                {{ $agents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
