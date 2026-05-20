@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Tickets</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage your support tickets</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-calendar3 text-blue-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                    </div>
                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock text-blue-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('user.tickets.index') }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Tickets</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 stat-total">{{ $stats['total'] ?? $tickets->total() }}</p>
                    <p class="text-xs text-gray-400 mt-1">All your tickets</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-ticket-detailed text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </a>
        
        <a href="{{ route('user.tickets.index', ['status' => 'open']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Open</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 stat-open">{{ $stats['open'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting response</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="bi bi-envelope-open text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('user.tickets.index', ['status' => 'pending']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 stat-pending">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Waiting for update</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="bi bi-clock text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </a>
        
        <a href="{{ route('user.tickets.index', ['status' => 'in_progress']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 stat-progress">{{ $stats['in_progress'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Being handled</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="bi bi-arrow-repeat text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </a>
        
        <a href="{{ route('user.tickets.index', ['status' => 'resolved']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Resolved</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 stat-resolved">{{ $stats['resolved'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Completed</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('user.tickets.index', ['status' => 'closed']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Closed</p>
                    <p class="text-3xl font-bold text-gray-600 dark:text-gray-400 stat-closed">{{ $stats['closed'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Archived</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="bi bi-archive text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('user.tickets.index', ['status' => 'canceled']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Canceled</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 stat-canceled">{{ $stats['canceled'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Canceled</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-x-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search by ticket #, title, or description..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-funnel absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="statusFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="all">All Status</option>
                            <option value="open">Open</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                </div>
                
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-flag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="priorityFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="all">All Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="categoryFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div>
                    <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
            </div>
            
            <div id="loadingIndicator" class="hidden mt-3 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                    <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading tickets...
                </div>
            </div>
        </div>
        
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() ?? 0 }} tickets
            </p>
        </div>
    </div>

    <!-- Tickets Table Container -->
    <div id="tableContainer">
        @include('user.tickets.partials.table', ['tickets' => $tickets])
    </div>
</div>

<script>
// Live Date and Time
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options);
    const formattedTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');
    if (dateElement) dateElement.textContent = formattedDate;
    if (timeElement) timeElement.textContent = formattedTime;
}

updateDateTime();
setInterval(updateDateTime, 1000);

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');
    let typingTimer;
    
    // Update stats from AJAX response
    function updateStats(data) {
        const statTotal = document.querySelector('.stat-total');
        const statOpen = document.querySelector('.stat-open');
        const statPending = document.querySelector('.stat-pending');
        const statProgress = document.querySelector('.stat-progress');
        const statResolved = document.querySelector('.stat-resolved');
        const statClosed = document.querySelector('.stat-closed');
        const statCanceled = document.querySelector('.stat-canceled');
        
        if (statTotal && data.stats?.total !== undefined) statTotal.textContent = data.stats.total;
        if (statOpen && data.stats?.open !== undefined) statOpen.textContent = data.stats.open;
        if (statPending && data.stats?.pending !== undefined) statPending.textContent = data.stats.pending;
        if (statProgress && data.stats?.in_progress !== undefined) statProgress.textContent = data.stats.in_progress;
        if (statResolved && data.stats?.resolved !== undefined) statResolved.textContent = data.stats.resolved;
        if (statClosed && data.stats?.closed !== undefined) statClosed.textContent = data.stats.closed;
        if (statCanceled && data.stats?.canceled !== undefined) statCanceled.textContent = data.stats.canceled;
    }
    
    function fetchTickets() {
        // Only show loading indicator, no fading
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            status: statusFilter?.value || 'all',
            priority: priorityFilter?.value || 'all',
            category: categoryFilter?.value || '',
            ajax: 1
        });
        
        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (tableContainer) {
                tableContainer.innerHTML = data.html;
            }
            if (resultsCount && data.results_count) {
                resultsCount.innerHTML = data.results_count;
            }
            updateStats(data);
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchTickets, 500);
        });
    }
    
    if (statusFilter) statusFilter.addEventListener('change', fetchTickets);
    if (priorityFilter) priorityFilter.addEventListener('change', fetchTickets);
    if (categoryFilter) categoryFilter.addEventListener('change', fetchTickets);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = 'all';
            if (priorityFilter) priorityFilter.value = 'all';
            if (categoryFilter) categoryFilter.value = '';
            fetchTickets();
        });
    }
});
</script>
@endsection