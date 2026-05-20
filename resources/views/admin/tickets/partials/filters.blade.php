<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
    <div class="p-4">
        <div class="flex items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input id="searchInput" type="text" name="search" placeholder="Search tickets..." class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm" />
                </div>
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
        <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">Showing results</p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('searchInput');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const tableContainer = document.getElementById('tableContainer');
    const resultsCount = document.getElementById('resultsCount');

    function fetchTickets() {
        loadingIndicator.classList.remove('hidden');
        tableContainer.style.opacity = '0.5';

        const params = new URLSearchParams({
            search: searchInput?.value || '',
            ajax: 1
        });

        fetch(`${window.location.pathname}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.html) tableContainer.innerHTML = data.html;
            if (resultsCount && data.results_count) resultsCount.innerHTML = data.results_count;
        })
        .catch(console.error)
        .finally(() => {
            loadingIndicator.classList.add('hidden');
            tableContainer.style.opacity = '1';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchTickets, 500);
        });
    }
});
</script>
@endpush
