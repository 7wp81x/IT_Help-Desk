@extends('admin.users.index')

@section('title', 'Support Agents')

@section('user-content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Support Agents</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage support agents and their performance</p>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-calendar3 text-orange-500 text-lg"></i>
                    <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                </div>
                <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-clock text-orange-500 text-lg"></i>
                    <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Agents</p>
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['agents'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Active support team members</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                <i class="bi bi-headset text-orange-600 dark:text-orange-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Online Now</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['online_agents'] ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1">Available for support</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <i class="bi bi-wifi text-green-600 dark:text-green-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tickets Assigned</p>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_assigned'] ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1">Currently assigned tickets</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <i class="bi bi-ticket-detailed text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Avg Response Time</p>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['avg_response_time'] ?? '2.5h' }}</p>
                <p class="text-xs text-gray-400 mt-1">Average response time</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="bi bi-stopwatch text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Agent Features Navigation -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('admin.users.agents.assignments') }}" 
       class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950/30 dark:to-blue-900/20 rounded-xl shadow-sm p-4 border border-blue-200 dark:border-blue-800 hover:shadow-md transition-all group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 transition">
                <i class="bi bi-ticket-perforated text-blue-600 text-lg"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Assignments</h3>
                <p class="text-xs text-gray-500">View/manage assigned tickets</p>
            </div>
            <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>
    
    <a href="{{ route('admin.users.agents.performance') }}" 
       class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950/30 dark:to-green-900/20 rounded-xl shadow-sm p-4 border border-green-200 dark:border-green-800 hover:shadow-md transition-all group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 transition">
                <i class="bi bi-graph-up text-green-600 text-lg"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Performance</h3>
                <p class="text-xs text-gray-500">Agent performance metrics</p>
            </div>
            <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>
    
    <a href="{{ route('admin.users.agents.schedule') }}" 
       class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950/30 dark:to-purple-900/20 rounded-xl shadow-sm p-4 border border-purple-200 dark:border-purple-800 hover:shadow-md transition-all group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center group-hover:bg-purple-200 transition">
                <i class="bi bi-calendar-week text-purple-600 text-lg"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Schedule</h3>
                <p class="text-xs text-gray-500">View shift schedules</p>
            </div>
            <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>
    
    <a href="{{ route('admin.users.agents.team-view') }}" 
       class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-950/30 dark:to-orange-900/20 rounded-xl shadow-sm p-4 border border-orange-200 dark:border-orange-800 hover:shadow-md transition-all group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center group-hover:bg-orange-200 transition">
                <i class="bi bi-people text-orange-600 text-lg"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Team View</h3>
                <p class="text-xs text-gray-500">View other agents</p>
            </div>
            <i class="bi bi-arrow-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>
</div>

<!-- FILTER BAR -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
    <div class="p-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search by name, email, or employee ID..." 
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
            
            <div class="w-[140px]">
                <div class="relative">
                    <i class="bi bi-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <select id="statusFilter" class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white cursor-pointer">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            
            <div>
                <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>
    
    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
        <p class="text-xs text-gray-500 dark:text-gray-400" id="resultsCount">
            Showing {{ $agents->firstItem() ?? 0 }} to {{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }} agents
        </p>
    </div>
</div>

<div id="statusMessage" class="hidden mb-6 rounded-xl p-3 text-sm"></div>

<!-- Agents Table - Natural sizing -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Agent Accounts</h3>
        <a href="{{ route('admin.users.create', ['role' => 'agent']) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg text-sm transition-all shadow-sm">
            <i class="bi bi-plus-circle"></i> Add Agent
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <div class="min-w-[1100px]">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Agent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Specialization</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Tickets</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[120px]">Rating</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white text-base font-bold shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $agent->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $agent->position ?? 'Support Agent' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $agent->email }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                <i class="bi bi-tag text-xs"></i>
                                {{ $agent->specialization ?? 'General Support' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 user-status-cell whitespace-nowrap">
                            @if($agent->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <i class="bi bi-circle-fill text-xs"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    <i class="bi bi-circle-fill text-xs"></i>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                <i class="bi bi-ticket text-xs"></i>
                                {{ $agent->tickets_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round(($agent->rating ?? 4.5)))
                                            <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="bi bi-star text-yellow-400 text-xs"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">{{ number_format($agent->rating ?? 4.5, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button"
                                        class="toggle-status-btn w-8 h-8 rounded-lg transition-all duration-200 flex items-center justify-center group"
                                        data-id="{{ $agent->id }}"
                                        data-active="{{ $agent->status === 'active' ? 'true' : 'false' }}"
                                        data-self="{{ $agent->id === auth()->id() ? 'true' : 'false' }}"
                                        title="{{ $agent->id === auth()->id() ? 'You cannot change the status of your own account.' : ($agent->status === 'active' ? 'Deactivate Agent' : 'Activate Agent') }}">
                                    @if($agent->status === 'active')
                                        <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 flex items-center justify-center">
                                            <i class="bi bi-toggle-on text-base"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center">
                                            <i class="bi bi-toggle-off text-base"></i>
                                        </div>
                                    @endif
                                </button>
                                <a href="{{ route('admin.users.show', $agent) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
                                   title="View Agent">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $agent) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
                                   title="Edit Agent">
                                    <i class="bi bi-pencil-square text-sm"></i>
                                </a>
                                <button onclick="showDeleteModal('{{ $agent->id }}', '{{ addslashes($agent->name) }}')" 
                                        class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                        title="Delete Agent">
                                    <i class="bi bi-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <i class="bi bi-headset text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No agent accounts found</p>
                                <a href="{{ route('admin.users.create', ['role' => 'agent']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg text-sm transition-all">
                                    <i class="bi bi-plus-circle"></i> Create your first agent
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($agents->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $agents->appends(request()->only('search', 'status'))->links() }}
    </div>
    @endif
</div>

@include('admin.users.partials.modals')

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

// Filter and Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetFilters');
    const resultsCount = document.getElementById('resultsCount');
    const statusMessage = document.getElementById('statusMessage');
    let typingTimer;
    
    function showStatusMessage(type, message) {
        if (!statusMessage) return;
        statusMessage.textContent = message;
        statusMessage.className = 'mb-6 rounded-xl p-3 text-sm border';
        if (type === 'success') {
            statusMessage.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
        } else {
            statusMessage.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
        }
        statusMessage.classList.remove('hidden');
        clearTimeout(showStatusMessage.timer);
        showStatusMessage.timer = setTimeout(() => {
            statusMessage.classList.add('hidden');
        }, 5000);
    }
    
    function applyFilters() {
        const params = new URLSearchParams({
            search: searchInput?.value || '',
            status: statusFilter?.value || 'all'
        });
        window.location.href = window.location.pathname + '?' + params.toString();
    }
    
    function attachToggleEvents() {
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.removeEventListener('click', handleToggleClick);
            btn.addEventListener('click', handleToggleClick);
        });
    }
    
    function updateToggleButton(button, isActive) {
        button.dataset.active = isActive ? 'true' : 'false';
        button.title = isActive ? 'Deactivate Agent' : 'Activate Agent';
        button.innerHTML = isActive
            ? '<div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 flex items-center justify-center"><i class="bi bi-toggle-on text-base"></i></div>'
            : '<div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center"><i class="bi bi-toggle-off text-base"></i></div>';
    }
    
    function updateStatusCell(row, isActive) {
        const statusCell = row.querySelector('.user-status-cell');
        if (!statusCell) return;
        statusCell.innerHTML = isActive
            ? '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400"><i class="bi bi-circle-fill text-xs"></i>Active</span>'
            : '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400"><i class="bi bi-circle-fill text-xs"></i>Inactive</span>';
    }
    
    function handleToggleClick(event) {
        event.preventDefault();
        const button = this;

        if (button.dataset.self === 'true') {
            showStatusMessage('error', 'You cannot change the status of your own account.');
            return;
        }

        const userId = button.dataset.id;
        const isActive = button.dataset.active === 'true';
        
        fetch(`/admin/users/${userId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatusMessage('success', data.message || 'Agent status updated successfully.');
                const row = button.closest('tr');
                updateToggleButton(button, !isActive);
                updateStatusCell(row, !isActive);
            } else {
                showStatusMessage('error', data.message || 'Error updating status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatusMessage('error', 'An error occurred while updating status.');
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(applyFilters, 500);
        });
    }
    
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }
    
    attachToggleEvents();
});
</script>
@endsection