<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Popular Categories -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-tags text-green-500 text-xl"></i>
            Popular Categories
        </h3>
        <div class="space-y-4">
            @forelse($popularCategories as $index => $category)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 dark:text-gray-300">{{ $category['name'] }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $category['count'] }} tickets</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    @php
                        $max = !empty($popularCategories) ? max(array_column($popularCategories, 'count')) : 1;
                        $percentage = $max > 0 ? ($category['count'] / $max) * 100 : 0;
                    @endphp
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="bi bi-tags text-4xl mb-2 block"></i>
                <p>No category data available</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Resolution Time by Category -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-hourglass-split text-orange-500 text-xl"></i>
            Resolution Time by Category
        </h3>
        <div class="space-y-4">
            @forelse($resolutionTimes as $category)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 dark:text-gray-300">{{ $category['name'] }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ number_format($category['hours'], 1) }} hours</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    @php
                        $maxTime = !empty($resolutionTimes) ? max(array_column($resolutionTimes, 'hours')) : 1;
                        $percentage = $maxTime > 0 ? ($category['hours'] / $maxTime) * 100 : 0;
                    @endphp
                    <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="bi bi-clock text-4xl mb-2 block"></i>
                <p>No resolution time data available</p>
            </div>
            @endforelse
        </div>
    </div>
</div>