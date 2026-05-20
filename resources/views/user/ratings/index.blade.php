@extends('layouts.app')

@section('title', 'My Ratings')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Ratings</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your agent ratings and feedback</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-calendar3 text-yellow-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                    </div>
                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock text-yellow-500 text-lg"></i>
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
                <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="bi bi-star-fill text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Ratings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-total">{{ $ratings->total() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Feedback submitted</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="bi bi-hand-thumbs-up-fill text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">5 Star Ratings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-5star">{{ $ratings->total() ? $ratings->where('rating', 5)->count() : 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Excellent service</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="bi bi-chat-fill text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">With Comments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-comments">{{ $ratings->total() ? $ratings->whereNotNull('comment')->count() : 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Detailed feedback</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-bar-chart text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Average Rating</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-avg">
                        @if($ratings->total())
                            {{ number_format($ratings->avg('rating'), 1) }}
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Overall satisfaction</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-950/30 dark:to-orange-950/30">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Ratings</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Search and filter your submitted ratings</p>
                </div>
                <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Ratings</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text"
                               id="searchInput"
                               placeholder="Agent name, ticket..."
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500">
                    </div>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Rating</label>
                    <div class="relative">
                        <i class="bi bi-star-fill absolute left-3 top-1/2 -translate-y-1/2 text-yellow-400 text-sm"></i>
                        <select id="ratingFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                    <div class="relative">
                        <i class="bi bi-arrow-down-up absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <select id="sortFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500">
                            <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Most Recent</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="rating_high" {{ request('sort') === 'rating_high' ? 'selected' : '' }}>Highest Rating</option>
                            <option value="rating_low" {{ request('sort') === 'rating_low' ? 'selected' : '' }}>Lowest Rating</option>
                        </select>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden flex items-center justify-center">
                    <div class="inline-flex items-center gap-2 text-sm text-gray-500">
                        <div class="w-4 h-4 border-2 border-yellow-600 border-t-transparent rounded-full animate-spin"></div>
                        Loading...
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
            <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
                Showing {{ $ratings->firstItem() ?? 0 }} to {{ $ratings->lastItem() ?? 0 }} of {{ $ratings->total() }} ratings
            </p>
        </div>
    </div>

    <!-- Ratings List -->
    <div id="ratingsContainer" class="space-y-4">
        @include('user.ratings.cards')
    </div>

    <!-- Pagination -->
    @if($ratings->hasPages())
        <div class="mt-8" id="paginationContainer">
            {{ $ratings->links() }}
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
    const ratingFilter = document.getElementById('ratingFilter');
    const sortFilter = document.getElementById('sortFilter');
    const resetBtn = document.getElementById('resetFilters');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const ratingsContainer = document.getElementById('ratingsContainer');
    const resultsCount = document.getElementById('resultsCount');
    const paginationContainer = document.getElementById('paginationContainer');
    let typingTimer;

    function fetchRatings() {
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (ratingsContainer) ratingsContainer.style.opacity = '0.5';

        const params = new URLSearchParams({
            search: searchInput?.value || '',
            rating: ratingFilter?.value || '',
            sort: sortFilter?.value || 'recent',
            ajax: 1
        });

        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (ratingsContainer && data.html) {
                ratingsContainer.innerHTML = data.html;
                ratingsContainer.style.opacity = '1';
            }
            if (resultsCount && data.results_count) {
                resultsCount.innerHTML = data.results_count;
            }
            if (paginationContainer && data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            if (data.stats) {
                const statTotal = document.querySelector('.stat-total');
                const stat5star = document.querySelector('.stat-5star');
                const statComments = document.querySelector('.stat-comments');
                const statAvg = document.querySelector('.stat-avg');
                if (statTotal) statTotal.textContent = data.stats.total;
                if (stat5star) stat5star.textContent = data.stats['5star'];
                if (statComments) statComments.textContent = data.stats.comments;
                if (statAvg) statAvg.textContent = data.stats.avg;
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
            typingTimer = setTimeout(fetchRatings, 500);
        });
    }

    if (ratingFilter) ratingFilter.addEventListener('change', fetchRatings);
    if (sortFilter) sortFilter.addEventListener('change', fetchRatings);

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (ratingFilter) ratingFilter.value = '';
            if (sortFilter) sortFilter.value = 'recent';
            fetchRatings();
        });
    }
});
</script>
@endsection