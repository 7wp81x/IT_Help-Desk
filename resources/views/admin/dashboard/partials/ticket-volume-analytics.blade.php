<!-- ============================================================ -->
<!-- ROW 1: RECENT TICKETS - FULL WIDTH (SCROLLABLE)              -->
<!-- ============================================================ -->
<div class="mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-clock-history text-blue-500 text-xl"></i>
                    Recent Tickets
                </h3>
                <a href="{{ route('admin.tickets.all') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 transition-colors">
                    View All Tickets →
                </a>
            </div>
        </div>
        
        @if($recentTickets->count() > 0)
        <div class="overflow-x-auto overflow-y-auto" style="max-height: 400px;">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 z-10">
                    <tr class="text-left">
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($recentTickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm font-mono font-medium text-gray-900 dark:text-white">#{{ $ticket->ticket_number ?? $ticket->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline line-clamp-1">
                                {{ $ticket->subject }}
                            </a>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-medium">
                                    {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->user->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full font-medium 
                                @if($ticket->status == 'open') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($ticket->status == 'resolved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $ticket->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="h-64 flex items-center justify-center">
            <div class="text-center">
                <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="bi bi-inbox text-xl text-gray-400"></i>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">No tickets found yet.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ============================================================ -->
<!-- ROW 2: TICKET TRENDS + STATUS DISTRIBUTION (BOTH SCROLLABLE) -->
<!-- ============================================================ -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Ticket Trends - SCROLLABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 overflow-y-auto" style="max-height: 400px;">
        <div class="flex items-center justify-between mb-3 sticky top-0 bg-white dark:bg-gray-800 pt-0">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-graph-up text-blue-500 text-lg"></i>
                Ticket Trends
            </h3>
            <select id="trendsPeriod" class="text-xs border border-gray-300 rounded-lg px-2 py-1 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                <option value="7">7 Days</option>
                <option value="30">30 Days</option>
                <option value="90">90 Days</option>
            </select>
        </div>
        <div style="height: 280px;">
            <canvas id="ticketTrendsChart"></canvas>
        </div>
    </div>

    <!-- Status Distribution - SCROLLABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 overflow-y-auto" style="max-height: 400px;">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-3 sticky top-0 bg-white dark:bg-gray-800 pt-0">
            <i class="bi bi-pie-chart text-purple-500 text-lg"></i>
            Status Distribution
        </h3>
        <div style="height: 250px;">
            <canvas id="ticketStatusChart"></canvas>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2 text-center">
            <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <p class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($stats['open_tickets'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">Open</p>
            </div>
            <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($stats['in_progress_tickets'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">In Progress</p>
            </div>
            <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($stats['resolved_tickets'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">Resolved</p>
            </div>
            <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <p class="text-lg font-bold text-gray-600 dark:text-gray-400">{{ number_format($stats['closed_tickets'] ?? 0) }}</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">Closed</p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- ROW 3: TOP PERFORMING AGENTS + AGENT WORKLOAD (BOTH SCROLLABLE) -->
<!-- ============================================================ -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Performing Agents - SCROLLABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4 sticky top-0 bg-white dark:bg-gray-800 pt-0">
            <i class="bi bi-trophy text-yellow-500 text-xl"></i>
            Top Performing Agents
        </h3>
        <div class="space-y-3 overflow-y-auto" style="max-height: 320px;">
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

    <!-- Agent Workload Balance - SCROLLABLE -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4 sticky top-0 bg-white dark:bg-gray-800 pt-0">
            <i class="bi bi-bar-chart-steps text-orange-500 text-xl"></i>
            Agent Workload Balance
        </h3>
        <div class="space-y-4 overflow-y-auto" style="max-height: 320px;">
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