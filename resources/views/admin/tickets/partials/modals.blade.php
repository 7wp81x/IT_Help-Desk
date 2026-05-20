@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.tickets.all') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                ← Back to Tickets
            </a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ticket->ticket_number }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $ticket->subject }}</p>
                @if($ticket->status === 'canceled')
                    <div class="mt-3 rounded-lg bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                        <i class="bi bi-info-circle mr-1"></i>
                        This ticket has been canceled. No further actions can be taken.
                    </div>
                @endif
            </div>
            
            <div class="flex gap-2 items-center">
                @if($ticket->status !== 'canceled')
                    <button onclick="changeStatus()" 
                            class="px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                        Change Status
                    </button>
                    <button onclick="assignAgent()" 
                            class="px-3 py-1.5 text-sm bg-green-600 hover:bg-green-700 text-white rounded-md transition">
                        Assign Agent
                    </button>
                    <form action="{{ route('admin.tickets.cancel', $ticket->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Are you sure you want to cancel this ticket? This action cannot be undone and will notify the requester.')"
                                class="px-3 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                            Cancel Ticket
                        </button>
                    </form>
                @else
                    <span class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md">Canceled</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Ticket Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket Information</h3>
                </div>
                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Comments/Replies -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversation History</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($ticket->comments as $comment)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($comment->user->name ?? 'S', 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $comment->user->name ?? 'System' }}</span>
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Meta Information -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status & Priority</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="mt-1">
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-100 text-yellow-800',
                                    'assigned' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'closed' => 'bg-gray-100 text-gray-800',
                                    'canceled' => 'bg-red-100 text-red-800',
                                ];
                                $statusColor = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Priority</label>
                        @php
                            $priorityColors = [
                                'low' => 'bg-gray-100 text-gray-800',
                                'medium' => 'bg-blue-100 text-blue-800',
                                'high' => 'bg-orange-100 text-orange-800',
                                'urgent' => 'bg-red-100 text-red-800',
                            ];
                            $priorityColor = $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColor }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Assignment Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->category->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Assigned Agent</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</p>
                    </div>
                </div>
            </div>

            <!-- Requester Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Requester</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ticket->created_at->format('F j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal - Centered -->
<div id="statusModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Change Status</h3>
            <button type="button" onclick="closeStatusModal()" class="text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <select id="statusSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="canceled" {{ $ticket->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>
        <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700">
            <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>
            <button type="button" onclick="submitStatusChange()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Save</button>
        </div>
    </div>
</div>

<!-- Assign Agent Modal - Centered -->
<div id="assignModal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assign Agent</h3>
            <button type="button" onclick="closeAssignModal()" class="text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Department</label>
                <select id="departmentSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose department...</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Category</label>
                <select id="categorySelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose category...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" data-department-id="{{ $category->department_id ?? '' }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Agent</label>
                <select id="agentSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose an agent...</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" data-department-id="{{ $agent->department_id ?? '' }}">
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700">
            <button type="button" onclick="closeAssignModal()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>
            <button type="button" onclick="submitAssignForm()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Assign Agent</button>
        </div>
    </div>
</div>

<script>
// Status Modal Functions
function changeStatus() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'items-center', 'justify-center');
    }
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'items-center', 'justify-center');
    }
}

function submitStatusChange() {
    const status = document.getElementById('statusSelect').value;
    const ticketId = {{ $ticket->id }};
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>';
    
    fetch(`/admin/tickets/${ticketId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update status'));
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating status.');
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

// Assign Agent Modal Functions
function assignAgent() {
    const modal = document.getElementById('assignModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'items-center', 'justify-center');
        
        // Reset selections
        const departmentSelect = document.getElementById('departmentSelect');
        if (departmentSelect) departmentSelect.value = '';
        
        // Trigger filter to show all options
        filterCategoriesAndAgents();
    }
}

function closeAssignModal() {
    const modal = document.getElementById('assignModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex', 'items-center', 'justify-center');
    }
}

// Store all options for filtering
let allCategories = [];
let allAgents = [];

function filterCategoriesAndAgents() {
    const departmentSelect = document.getElementById('departmentSelect');
    const categorySelect = document.getElementById('categorySelect');
    const agentSelect = document.getElementById('agentSelect');
    
    if (!departmentSelect || !categorySelect || !agentSelect) return;
    
    const selectedDepartment = departmentSelect.value;
    
    // Filter categories
    categorySelect.innerHTML = '<option value="">Choose category...</option>';
    allCategories.forEach(option => {
        if (option.value === '') return;
        const categoryDeptId = option.getAttribute('data-department-id');
        if (!selectedDepartment || categoryDeptId === selectedDepartment) {
            categorySelect.appendChild(option.cloneNode(true));
        }
    });
    
    // Filter agents
    agentSelect.innerHTML = '<option value="">Choose an agent...</option>';
    allAgents.forEach(option => {
        if (option.value === '') return;
        const agentDeptId = option.getAttribute('data-department-id');
        if (!selectedDepartment || agentDeptId === selectedDepartment) {
            agentSelect.appendChild(option.cloneNode(true));
        }
    });
}

function submitAssignForm() {
    const departmentId = document.getElementById('departmentSelect').value;
    const categoryId = document.getElementById('categorySelect').value;
    const agentId = document.getElementById('agentSelect').value;
    const ticketId = {{ $ticket->id }};
    const assignBtn = event.target;
    const originalText = assignBtn.innerHTML;
    
    if (!departmentId) {
        alert('Please select a department.');
        return;
    }
    if (!categoryId) {
        alert('Please select a category.');
        return;
    }
    if (!agentId) {
        alert('Please select an agent.');
        return;
    }
    
    assignBtn.disabled = true;
    assignBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>';
    
    fetch(`/admin/tickets/${ticketId}/assign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            department_id: departmentId,
            category_id: categoryId,
            assigned_to: agentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to assign agent'));
            assignBtn.disabled = false;
            assignBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while assigning agent.');
        assignBtn.disabled = false;
        assignBtn.innerHTML = originalText;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('departmentSelect');
    const categorySelect = document.getElementById('categorySelect');
    const agentSelect = document.getElementById('agentSelect');
    
    if (departmentSelect && categorySelect) {
        // Store all options
        allCategories = Array.from(categorySelect.options);
        allAgents = Array.from(agentSelect.options);
        
        departmentSelect.addEventListener('change', filterCategoriesAndAgents);
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const statusModal = document.getElementById('statusModal');
    const assignModal = document.getElementById('assignModal');
    
    if (e.target === statusModal) closeStatusModal();
    if (e.target === assignModal) closeAssignModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeStatusModal();
        closeAssignModal();
    }
});
</script>
@endsection