<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- CSAT Score Dashboard -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-emoji-smile text-yellow-500 text-xl"></i>
            Customer Satisfaction
        </h3>
        <div class="flex flex-col items-center text-center">
            <div class="text-5xl font-bold text-yellow-500 mb-2">
                {{ number_format($csatScore ?? 4.2, 1) }} <span class="text-2xl text-gray-400">/ 5</span>
            </div>
            <div class="flex items-center gap-1 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star-fill text-2xl {{ $i <= round($csatScore ?? 4.2) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                @endfor
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 dark:bg-green-900/30 rounded-full">
                <i class="bi bi-graph-up text-green-600"></i>
                <span class="text-sm text-green-700 dark:text-green-400">{{ $csatTrend ?? '+5' }}% from last month</span>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-4 w-full">
                <div class="text-center">
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalRatings ?? 42 }}</p>
                    <p class="text-xs text-gray-500">Ratings</p>
                </div>
                <div class="text-center">
                    <p class="text-xl font-bold text-green-600">{{ $positiveRate ?? 78 }}%</p>
                    <p class="text-xs text-gray-500">Positive</p>
                </div>
                <div class="text-center">
                    <p class="text-xl font-bold text-red-600">{{ $negativeRate ?? 22 }}%</p>
                    <p class="text-xs text-gray-500">Negative</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-bar-chart text-purple-500 text-xl"></i>
            Rating Distribution
        </h3>
        <div class="space-y-3">
            @foreach([5,4,3,2,1] as $star)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <div class="flex items-center gap-1">
                        <span class="text-gray-700 dark:text-gray-300">{{ $star }} Star</span>
                        <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400">{{ $ratingDistribution[$star] ?? 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $ratingDistribution[$star] ?? 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>