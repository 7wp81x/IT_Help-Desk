@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    @include('admin.dashboard.partials.header')
    @include('admin.dashboard.partials.stats-cards')
    
    
    <!-- Analytics Sections -->
    @include('admin.dashboard.partials.ticket-volume-analytics')
    @include('admin.dashboard.partials.agent-performance')
    @include('admin.dashboard.partials.sla-compliance')
    @include('admin.dashboard.partials.customer-satisfaction')
    @include('admin.dashboard.partials.category-analytics')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateDateTime() {
        const now = new Date();
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        
        const dateElement = document.getElementById('currentDate');
        const timeElement = document.getElementById('currentTime');
        
        if (dateElement) dateElement.textContent = now.toLocaleDateString('en-US', dateOptions);
        if (timeElement) timeElement.textContent = now.toLocaleTimeString('en-US', timeOptions);
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    document.addEventListener('DOMContentLoaded', function() {
        // Ticket Trends Chart
        const trendsData = @json($trends ?? []);
        if (document.getElementById('ticketTrendsChart') && trendsData.dates) {
            new Chart(document.getElementById('ticketTrendsChart'), {
                type: 'line',
                data: {
                    labels: trendsData.dates,
                    datasets: [{
                        label: 'Tickets Created',
                        data: trendsData.counts,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }
        
        // Ticket Status Chart
        const statusData = @json($statusCounts ?? []);
        if (document.getElementById('ticketStatusChart') && statusData.labels) {
            new Chart(document.getElementById('ticketStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.counts,
                        backgroundColor: ['rgb(234, 179, 8)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)', 'rgb(107, 114, 128)'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>
@endpush