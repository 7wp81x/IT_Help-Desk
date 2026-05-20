@extends('layouts.app')

@section('title', 'All Tickets')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">All Tickets</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage all support tickets</p>
        </div>
        
      
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 mb-6" id="stats-container">
        <a href="{{ route('admin.tickets.all') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 stat-total">{{ $stats['total'] ?? $tickets->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                    <i class="bi bi-ticket-detailed text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.tickets.open') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Open</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1 stat-open">{{ $stats['open'] ?? \App\Models\Ticket::whereIn('status', ['open', 'assigned'])->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/50 transition-colors">
                    <i class="bi bi-envelope-open text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.tickets.all', ['status' => 'pending']) }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1 stat-pending">{{ $stats['pending'] ?? \App\Models\Ticket::whereIn('status', ['pending', 'pending_user_response', 'pending_admin_approval'])->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center group-hover:bg-orange-200 dark:group-hover:bg-orange-800/50 transition-colors">
                    <i class="bi bi-clock text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.tickets.in-progress') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1 stat-progress">{{ $stats['in_progress'] ?? \App\Models\Ticket::where('status', 'in_progress')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                    <i class="bi bi-arrow-repeat text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.tickets.resolved') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Resolved</p>
                    <p class="text-2xl font-bold text-green-600 mt-1 stat-resolved">{{ $stats['resolved'] ?? \App\Models\Ticket::where('status', 'resolved')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors">
                    <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.tickets.closed') }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Closed</p>
                    <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-1 stat-closed">{{ $stats['closed'] ?? \App\Models\Ticket::where('status', 'closed')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                    <i class="bi bi-archive text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.tickets.all', ['status' => 'canceled']) }}" 
           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Canceled</p>
                    <p class="text-2xl font-bold text-gray-600 mt-1 stat-canceled">{{ $stats['canceled'] ?? \App\Models\Ticket::where('status', 'canceled')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
                    <i class="bi bi-x-circle text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Single Filter Bar - All filters in one row -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search by subject, ID, or requester..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="statusFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                </div>
                
                <!-- Priority Filter -->
                <div class="w-[140px]">
                    <div class="relative">
                        <i class="bi bi-flag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="priorityFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="w-[160px]">
                    <div class="relative">
                        <i class="bi bi-folder absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="categoryFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach($categories ?? \App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Reset Button - Inline -->
                <div>
                    <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </button>
                </div>
            </div>
            
            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="hidden mt-3 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                    <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading tickets...
                </div>
            </div>
        </div>
        
        <!-- Results Count -->
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
            </p>
        </div>
    </div>

    <!-- Tickets Table Container -->
    <div id="tableContainer">
        @include('admin.tickets.partials.table', ['tickets' => $tickets])
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');
    
    function fetchTickets() {
        // Show loading
        loadingIndicator.classList.remove('hidden');
        tableContainer.style.opacity = '0.5';
        
        // Get filter values
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            status: statusFilter?.value || '',
            priority: priorityFilter?.value || '',
            category: categoryFilter?.value || '',
            ajax: 1
        });
        
        // Fetch filtered tickets
        fetch(`{{ route('admin.tickets.all') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table
            tableContainer.innerHTML = data.html;
            tableContainer.style.opacity = '1';
            
            // Update results count
            if (resultsCount) {
                resultsCount.innerHTML = data.results_count;
            }
            
            // Update stats
            if (data.stats) {
                if (document.querySelector('.stat-total')) document.querySelector('.stat-total').textContent = data.stats.total;
                if (document.querySelector('.stat-open')) document.querySelector('.stat-open').textContent = data.stats.open;
                if (document.querySelector('.stat-pending')) document.querySelector('.stat-pending').textContent = data.stats.pending;
                if (document.querySelector('.stat-progress')) document.querySelector('.stat-progress').textContent = data.stats.in_progress;
                if (document.querySelector('.stat-resolved')) document.querySelector('.stat-resolved').textContent = data.stats.resolved;
                if (document.querySelector('.stat-closed')) document.querySelector('.stat-closed').textContent = data.stats.closed;
                if (document.querySelector('.stat-canceled')) document.querySelector('.stat-canceled').textContent = data.stats.canceled;
            }
            
            // Re-attach event handlers for new checkboxes
            attachSelectAllHandler();
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            loadingIndicator.classList.add('hidden');
        });
    }
    
    function attachSelectAllHandler() {
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.onchange = function(e) {
                const checkboxes = document.querySelectorAll('.ticket-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
            };
        }
    }
    
    // Search input with debounce (500ms)
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchTickets, 500);
        });
    }
    
    // Filters - submit immediately on change
    if (statusFilter) statusFilter.addEventListener('change', fetchTickets);
    if (priorityFilter) priorityFilter.addEventListener('change', fetchTickets);
    if (categoryFilter) categoryFilter.addEventListener('change', fetchTickets);
    
    // Reset all filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (priorityFilter) priorityFilter.value = '';
            if (categoryFilter) categoryFilter.value = '';
            fetchTickets();
        });
    }
    
    // Initial load
    attachSelectAllHandler();
});
</script>

<style>
    #tableContainer {
        transition: opacity 0.2s ease;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 0.6s linear infinite;
    }
    
    input[type="checkbox"] {
        cursor: pointer;
        width: 16px;
        height: 16px;
    }
</style>
@endsection
