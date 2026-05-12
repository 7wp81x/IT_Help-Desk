<!-- Tickets Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket List</h3>
        <a href="{{ route('admin.tickets.create') }}" 
           class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
            <i class="bi bi-plus-circle"></i> New Ticket
        </a>
    </div>
    <div class="overflow-x-auto">
        <div class="min-w-[1100px]">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-4 w-12">
                            <input type="checkbox" id="selectAllCheckbox" 
                                class="appearance-none w-4 h-4 bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:border-red-500 checked:bg-transparent relative
                                after:content-['\2713'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[200px]">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Requester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Agent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Created</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                        <td class="px-4 py-4">
                            <input type="checkbox" 
                                class="ticket-checkbox appearance-none w-4 h-4 bg-transparent dark:bg-transparent border-2 border-gray-400 dark:border-gray-500 rounded focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all duration-150 hover:border-gray-500 dark:hover:border-gray-400 checked:bg-transparent checked:border-red-500 relative after:content-['\2713'] after:text-red-500 after:text-[10px] after:font-bold after:absolute after:hidden checked:after:block after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2" 
                                value="{{ $ticket->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">#{{ $ticket->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                               class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block max-w-[250px]" 
                               title="{{ $ticket->subject }}">
                                {{ \Illuminate\Support\Str::limit($ticket->subject, 50) }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($ticket->user->name ?? 'N/A', 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[120px]" title="{{ $ticket->user->name ?? 'N/A' }}">
                                    {{ $ticket->user->name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'resolved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'closed' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                ];
                                $statusIcons = [
                                    'open' => 'bi bi-circle',
                                    'in_progress' => 'bi bi-arrow-repeat',
                                    'resolved' => 'bi bi-check-circle-fill',
                                    'closed' => 'bi bi-check-circle',
                                ];
                                $statusText = ucfirst(str_replace('_', ' ', $ticket->status));
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }} whitespace-nowrap">
                                <i class="{{ $statusIcons[$ticket->status] ?? 'bi bi-circle' }} text-xs"></i>
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                    'medium' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'urgent' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $priorityIcons = [
                                    'low' => 'bi bi-arrow-down-circle-fill',
                                    'medium' => 'bi bi-circle',
                                    'high' => 'bi bi-arrow-up-circle-fill',
                                    'urgent' => 'bi bi-exclamation-triangle-fill',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg {{ $priorityColors[$ticket->priority] ?? 'bg-blue-100 text-blue-700' }} whitespace-nowrap">
                                <i class="{{ $priorityIcons[$ticket->priority] ?? 'bi bi-circle' }} text-xs"></i>
                                {{ ucfirst($ticket->priority ?? 'Normal') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 whitespace-nowrap">
                                <i class="bi bi-tag text-xs"></i>
                                {{ $ticket->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($ticket->assignedAgent)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($ticket->assignedAgent->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-[100px]" title="{{ $ticket->assignedAgent->name }}">
                                        {{ $ticket->assignedAgent->name }}
                                    </span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 whitespace-nowrap">
                                    <i class="bi bi-person-circle text-xs"></i>
                                    Unassigned
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $ticket->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
                                   title="View Ticket">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.tickets.edit', $ticket->id) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                                   title="Edit Ticket">
                                    <i class="bi bi-pencil-square text-sm"></i>
                                </a>
                                <button type="button" 
                                        class="delete-ticket-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                        data-id="{{ $ticket->id }}"
                                        data-name="{{ addslashes($ticket->subject) }}"
                                        title="Delete Ticket">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <i class="bi bi-ticket text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No tickets found</p>
                                <a href="{{ route('admin.tickets.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                                    <i class="bi bi-plus-circle"></i> Create your first ticket
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $tickets->links() }}
    </div>
    @endif
</div>