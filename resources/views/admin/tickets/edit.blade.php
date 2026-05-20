@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.tickets.all') }}" class="inline-flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="bi bi-arrow-left text-sm"></i>
                Back to Tickets
            </a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Ticket</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update ticket #{{ $ticket->ticket_number }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.tickets.show', $ticket) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bi bi-eye"></i>
                    View Ticket
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Ticket Information Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-info-circle mr-2"></i> Ticket Information
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="subject" 
                               value="{{ old('subject', $ticket->subject) }}" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                               placeholder="Enter ticket subject">
                        @error('subject') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  rows="6" 
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe the issue in detail...">{{ old('description', $ticket->description) }}</textarea>
                        @error('description') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Category & Priority Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-tags mr-2"></i> Category & Priority
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                        <select id="departmentFilter" name="department_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $ticket->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Select department to filter categories</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="categorySelect" name="category_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        data-department="{{ $category->department_id }}" 
                                        {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ old('status', $ticket->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="canceled" {{ old('status', $ticket->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                        @error('status') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-person-badge mr-2"></i> Assignment
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Assign To Agent
                        </label>
                        <select id="agentSelect" name="assigned_to" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Unassigned --</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" 
                                        data-department-id="{{ $agent->department_id }}">
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Agents are filtered by selected department</p>
                        @error('assigned_to') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Created By (Requester) <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $ticket->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 flex justify-end gap-3">
                <a href="{{ route('admin.tickets.all') }}" 
                   class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg shadow-sm transition">
                    <i class="bi bi-save mr-2"></i>
                    Update Ticket
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Store all agent options for filtering (SAME LOGIC as Ticket Details)
let allAgentOptions = [];

function filterAgents() {
    const departmentId = document.getElementById('departmentFilter').value;
    const agentSelect = document.getElementById('agentSelect');
    
    if (!agentSelect) return;
    
    // Store original options if not already stored
    if (allAgentOptions.length === 0) {
        allAgentOptions = Array.from(agentSelect.options);
    }
    
    // Clear current options but keep the first one (Unassigned)
    agentSelect.innerHTML = '<option value="">-- Unassigned --</option>';
    
    let hasMatchingAgent = false;
    
    // Filter and add matching agents
    allAgentOptions.forEach(option => {
        if (!option.value) return;
        
        const agentDeptId = option.getAttribute('data-department-id');
        
        // Check if agent belongs to selected department
        const matchesDepartment = !departmentId || agentDeptId === departmentId;
        
        if (matchesDepartment) {
            const newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.textContent = option.textContent;
            newOption.setAttribute('data-department-id', agentDeptId);
            
            // Check if this is the currently assigned agent
            if (option.value === "{{ $ticket->assigned_to }}") {
                newOption.selected = true;
            }
            
            agentSelect.appendChild(newOption);
            hasMatchingAgent = true;
        }
    });
    
    // If no matching agents found and we have department selected, show a message
    if (!hasMatchingAgent && departmentId) {
        const noOption = document.createElement('option');
        noOption.value = '';
        noOption.textContent = '⚠️ No agents available for this department';
        noOption.disabled = true;
        noOption.style.color = '#ef4444';
        agentSelect.appendChild(noOption);
    }
}

// Function to filter categories based on department (SAME as Ticket Details)
function filterCategories() {
    const selectedDepartment = document.getElementById('departmentFilter').value;
    const categorySelect = document.getElementById('categorySelect');
    
    if (!categorySelect) return;
    
    Array.from(categorySelect.options).forEach(option => {
        if (!option.value) {
            option.hidden = false;
            return;
        }
        const departmentId = option.getAttribute('data-department');
        option.hidden = selectedDepartment && selectedDepartment !== departmentId;
    });

    if (categorySelect.selectedOptions.length > 0 && categorySelect.selectedOptions[0].hidden) {
        categorySelect.value = '';
    }
    
    // Trigger agent filter when department changes
    filterAgents();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const departmentFilter = document.getElementById('departmentFilter');
    const categorySelect = document.getElementById('categorySelect');
    
    if (departmentFilter && categorySelect) {
        // Add event listeners
        departmentFilter.addEventListener('change', function() {
            filterCategories();
        });
        
        // Initial filter
        filterCategories();
    }
});
</script>
@endsection