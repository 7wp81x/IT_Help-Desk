<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Overall SLA Compliance -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-shield-check text-green-500 text-xl"></i>
            SLA Compliance
        </h3>
        <div class="flex flex-col items-center justify-center h-48">
            <div class="relative w-40 h-40">
                <svg class="w-full h-full transform -rotate-90">
                    <circle cx="80" cy="80" r="70" fill="none" stroke="#e5e7eb" stroke-width="12"/>
                    <circle cx="80" cy="80" r="70" fill="none" stroke="#22c55e" stroke-width="12"
                            stroke-dasharray="440" stroke-dashoffset="{{ 440 - (440 * ($slaCompliance / 100)) }}"
                            class="transition-all duration-1000"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $slaCompliance ?? 92 }}%</span>
                    <span class="text-xs text-gray-500">Compliance Rate</span>
                </div>
            </div>
            <p class="mt-3 text-sm text-gray-500">{{ $slaBreaches ?? 8 }}% Breaches</p>
        </div>
    </div>

    <!-- SLA Breaches by Priority -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle text-red-500 text-xl"></i>
            SLA Breaches by Priority
        </h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 dark:text-gray-300">High Priority</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $breachesByPriority['high'] ?? 0 }} breaches</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(100, ($breachesByPriority['high'] ?? 0) / ($breachesByPriority['max'] ?? 1) * 100) }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 dark:text-gray-300">Medium Priority</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $breachesByPriority['medium'] ?? 0 }} breaches</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(100, ($breachesByPriority['medium'] ?? 0) / ($breachesByPriority['max'] ?? 1) * 100) }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 dark:text-gray-300">Low Priority</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $breachesByPriority['low'] ?? 0 }} breaches</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(100, ($breachesByPriority['low'] ?? 0) / ($breachesByPriority['max'] ?? 1) * 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>