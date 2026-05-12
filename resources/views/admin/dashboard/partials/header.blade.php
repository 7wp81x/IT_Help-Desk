<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        
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