@extends('layouts.app')

@section('title', 'Agent Performance')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Agent Performance</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View your performance metrics and statistics</p>
        </div>
        <div>
            <a href="{{ route('admin.users.agents') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Tickets Resolved Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tickets Resolved</h3>
            <canvas id="resolvedChart" height="200"></canvas>
        </div>
        
        <!-- Response Time Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Average Response Time</h3>
            <canvas id="responseTimeChart" height="200"></canvas>
        </div>
    </div>

    <!-- Detailed Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $totalResolved }}</p>
                </div>
                <i class="bi bi-check2-circle text-2xl text-green-500"></i>
            </div>
            <p class="text-xs text-gray-400 mt-2">Last 30 days: +{{ $resolvedIncrease }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Customer Rating</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($avgRating, 1) }}</p>
                </div>
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                    @endfor
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Based on {{ $totalRatings }} ratings</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avg Resolution Time</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $avgResolutionTime }}</p>
                </div>
                <i class="bi bi-hourglass-split text-2xl text-blue-500"></i>
            </div>
            <p class="text-xs text-gray-400 mt-2">Faster than avg by {{ $fasterThanAvg }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Satisfaction Score</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $satisfactionScore }}%</p>
                </div>
                <i class="bi bi-emoji-smile text-2xl text-purple-500"></i>
            </div>
            <p class="text-xs text-gray-400 mt-2">Target: 90%</p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 dark:bg-gray-800/50">
            <h3 class="font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($activities as $activity)
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="bi bi-check-circle text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
                @if($activity->ticket)
                <a href="{{ route('admin.tickets.show', $activity->ticket) }}" class="text-sm text-blue-600 hover:underline">View Ticket →</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Resolved Tickets Chart
    new Chart(document.getElementById('resolvedChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Tickets Resolved',
                data: {!! json_encode($resolvedData) !!},
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
    
    // Response Time Chart
    new Chart(document.getElementById('responseTimeChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Response Time (hours)',
                data: {!! json_encode($responseTimeData) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
@endsection