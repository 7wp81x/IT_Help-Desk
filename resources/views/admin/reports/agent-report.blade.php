@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agent Performance Report
        </h2>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">Back to Reports</a>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Search -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" class="flex gap-4">
                    <input type="text" name="search" placeholder="Search agents..." value="{{ request('search') }}" class="flex-1 border rounded-lg px-3 py-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Search</button>
                    <a href="{{ route('admin.reports.agents') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>
        </div>
        
        <!-- Agents List -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($agents as $agent)
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white text-lg font-semibold">
                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ $agent->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $agent->email }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-6 text-center">
                                    <div>
                                        <p class="text-2xl font-bold text-blue-600">{{ $agent->assigned_tickets_count }}</p>
                                        <p class="text-xs text-gray-500">Assigned</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-green-600">{{ $agent->resolved_count ?? 0 }}</p>
                                        <p class="text-xs text-gray-500">Resolved</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-purple-600">{{ $agent->avg_resolution_time ?? 0 }}h</p>
                                        <p class="text-xs text-gray-500">Avg Resolution</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">{{ $agent->assigned_tickets_count > 0 ? round(($agent->resolved_count ?? 0) / $agent->assigned_tickets_count * 100) : 0 }}%</p>
                                        <p class="text-xs text-gray-500">Success Rate</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                @php $percentage = $agent->assigned_tickets_count > 0 ? (($agent->resolved_count ?? 0) / $agent->assigned_tickets_count) * 100 : 0; @endphp
                                <div class="bg-blue-600 h-2 rounded-full progress-bar" data-width="{{ $Percentage }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No agents found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection