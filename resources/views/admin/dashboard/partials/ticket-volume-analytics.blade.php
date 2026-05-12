<!-- Left Column: Recent Tickets (Top) + Ticket Trends (Bottom) -->
<!-- Right Column: Status Distribution (Full Height) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- LEFT COLUMN (Takes 1/2 width) -->
    <div class="space-y-6">
        <!-- Recent Tickets - TOP LEFT -->
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
            <div class="overflow-x-auto h-64 overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                        <tr class="text-left">
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        \)

                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentTickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2 whitespace-nowrap">
                                <span class="text-xs font-mono font-medium text-gray-900">#{{ $ticket->ticket_number ?? $ticket->id }}</span>
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-xs text-blue-600 hover:underline line-clamp-1">
                                    {{ $ticket->subject }}
                                </a>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-[10px] font-medium">
                                        {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $ticket->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-[10px] rounded-full font-medium 
                                    @if($ticket->status == 'open') bg-yellow-100 text-yellow-700
                                    @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700
                                    @elseif($ticket->status == 'resolved') bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                {{ $ticket->created_at->format('M d, Y') }}
                            </td>
                        \)

                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="h-64 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-inbox text-xl text-gray-400"></i>
                    </div>
                    <p class="text-xs text-gray-500">No tickets found yet.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Ticket Trends - BOTTOM LEFT -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-graph-up text-blue-500 text-lg"></i>
                    Ticket Trends
                </h3>
                <select id="trendsPeriod" class="text-xs border border-gray-300 rounded-lg px-2 py-1 bg-white text-gray-700">
                    <option value="7">7 Days</option>
                    <option value="30">30 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
            <div class="h-56">
                <canvas id="ticketTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Status Distribution (Same width as left column, full height matching both left boxes combined) -->
    <div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 h-full flex flex-col">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-3">
                <i class="bi bi-pie-chart text-purple-500 text-lg"></i>
                Status Distribution
            </h3>
            <div class="flex-1 min-h-0">
                <div class="h-56">
                    <canvas id="ticketStatusChart"></canvas>
                </div>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 text-center">
                <div class="p-2 bg-gray-50 rounded-lg">
                    <p class="text-lg font-bold text-yellow-600">{{ number_format($stats['open_tickets'] ?? 0) }}</p>
                    <p class="text-[10px] text-gray-500">Open</p>
                </div>
                <div class="p-2 bg-gray-50 rounded-lg">
                    <p class="text-lg font-bold text-blue-600">{{ number_format($stats['in_progress_tickets'] ?? 0) }}</p>
                    <p class="text-[10px] text-gray-500">In Progress</p>
                </div>
                <div class="p-2 bg-gray-50 rounded-lg">
                    <p class="text-lg font-bold text-green-600">{{ number_format($stats['resolved_tickets'] ?? 0) }}</p>
                    <p class="text-[10px] text-gray-500">Resolved</p>
                </div>
                <div class="p-2 bg-gray-50 rounded-lg">
                    <p class="text-lg font-bold text-gray-600">{{ number_format($stats['closed_tickets'] ?? 0) }}</p>
                    <p class="text-[10px] text-gray-500">Closed</p>
                </div>
            </div>
        </div>
    </div>
</div>