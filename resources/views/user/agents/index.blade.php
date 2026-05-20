@extends('layouts.app')

@section('title', 'Our Support Agents')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Our Support Agents</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Meet our dedicated support team ready to help you</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-calendar3 text-orange-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                    </div>
                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock text-orange-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="bi bi-people text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Agents</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-total">{{ $agents->total() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Available to help</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="bi bi-check-circle-fill text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Avg Response Time</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">~2.5h</p>
                    <p class="text-xs text-gray-400 mt-1">Quick support</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-ticket-detailed text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tickets Resolved</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-resolved">{{ number_format($agents->sum('total_resolved')) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total helped</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="bi bi-star-fill text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Avg Rating</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-rating">{{ number_format($agents->avg('average_rating') ?? 4.5, 1) }}/5</p>
                    <p class="text-xs text-gray-400 mt-1">Customer satisfaction</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-950/30 dark:to-red-950/30">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Find an Agent</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Search and filter to find the right support agent for your needs</p>
                </div>
                <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Agent</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text"
                               id="searchInput"
                               placeholder="Agent name, email..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                    <div class="relative">
                        <i class="bi bi-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="departmentFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer focus:ring-2 focus:ring-orange-500">
                            <option value="">All Departments</option>
                            <option value="Technical Support">Technical Support</option>
                            <option value="Customer Support">Customer Support</option>
                            <option value="Network Support">Network Support</option>
                            <option value="Software Support">Software Support</option>
                            <option value="Hardware Support">Hardware Support</option>
                        </select>
                    </div>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                    <div class="relative">
                        <i class="bi bi-arrow-down-up absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="sortFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer focus:ring-2 focus:ring-orange-500">
                            <option value="rating_desc">Highest Rating</option>
                            <option value="rating_asc">Lowest Rating</option>
                            <option value="resolved_desc">Most Resolved</option>
                            <option value="response_time">Fastest Response</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="loadingIndicator" class="hidden mt-3 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                    <div class="w-4 h-4 border-2 border-orange-600 border-t-transparent rounded-full animate-spin"></div>
                    Loading agents...
                </div>
            </div>
        </div>

        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $agents->firstItem() ?? 0 }} to {{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }} agents
            </p>
        </div>
    </div>

    <!-- Agents Grid Container -->
    <div id="agentsContainer">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @include('user.agents.cards')
        </div>
    </div>

    <!-- Pagination -->
    @if($agents->hasPages())
        <div class="mt-8" id="paginationContainer">
            {{ $agents->links() }}
        </div>
    @endif
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
    const departmentFilter = document.getElementById('departmentFilter');
    const sortFilter = document.getElementById('sortFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const agentsContainer = document.getElementById('agentsContainer');
    const resultsCount = document.getElementById('resultsCount');
    const paginationContainer = document.getElementById('paginationContainer');
    let typingTimer;

    function fetchAgents() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (agentsContainer) agentsContainer.style.opacity = '0.5';

        const params = new URLSearchParams({
            search: searchInput?.value || '',
            department: departmentFilter?.value || '',
            sort: sortFilter?.value || 'rating_desc',
            ajax: 1
        });

        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (agentsContainer && data.html) {
                // Wrap cards in grid structure
                const gridHtml = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">${data.html}</div>`;
                agentsContainer.innerHTML = gridHtml;
                agentsContainer.style.opacity = '1';
            }
            if (resultsCount && data.results_count) {
                resultsCount.innerHTML = data.results_count;
            }
            if (paginationContainer && data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            if (data.stats) {
                const statTotal = document.querySelector('.stat-total');
                const statResolved = document.querySelector('.stat-resolved');
                const statRating = document.querySelector('.stat-rating');
                if (statTotal) statTotal.textContent = data.stats.total;
                if (statResolved) statResolved.textContent = data.stats.resolved;
                if (statRating) statRating.textContent = data.stats.rating;
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchAgents, 500);
        });
    }

    if (departmentFilter) departmentFilter.addEventListener('change', fetchAgents);
    if (sortFilter) sortFilter.addEventListener('change', fetchAgents);

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (departmentFilter) departmentFilter.value = '';
            if (sortFilter) sortFilter.value = 'rating_desc';
            fetchAgents();
        });
    }
});
</script>

<style>
    #agentsContainer { transition: opacity 0.2s ease; }
    .animate-spin { animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection