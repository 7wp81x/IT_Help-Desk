<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Top Performing Agents -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-trophy text-yellow-500 text-xl"></i>
            Top Performing Agents
        </h3>
        <div class="space-y-3">
            @forelse($topAgents as $index => $agent)
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $agent['name'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $agent['email'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $agent['resolved_count'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Resolved</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="bi bi-people text-4xl mb-2 block"></i>
                <p>No agent data available</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Agent Workload Balance -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <i class="bi bi-bar-chart-steps text-orange-500 text-xl"></i>
            Agent Workload Balance
        </h3>
        <div class="space-y-4">
            @forelse($agentWorkload as $agent)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700 dark:text-gray-300">{{ $agent['name'] }}</span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $agent['open_tickets'] }} tickets</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    @php
                        $maxWorkload = max(array_column($agentWorkload, 'open_tickets')) ?: 1;
                        $percentage = ($agent['open_tickets'] / $maxWorkload) * 100;
                    @endphp
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="bi bi-bar-chart text-4xl mb-2 block"></i>
                <p>No workload data available</p>
            </div>
            @endforelse
        </div>
    </div>
</div>