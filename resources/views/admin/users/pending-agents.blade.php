@extends('admin.users.index')

@section('title', 'Pending Agent Approvals')

@section('user-content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pending Agent Approvals</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Review and approve user agent registration requests</p>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-2">
            <i class="bi bi-hourglass-split text-orange-500 text-lg"></i>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $totalPending }}</span>
            <span class="text-sm text-gray-600 dark:text-gray-400">Pending Approval</span>
        </div>
    </div>
</div>

<!-- Info Alert -->
@if($totalPending > 0)
<div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
    <div class="flex gap-3">
        <div class="text-blue-600 dark:text-blue-400 mt-1">
            <i class="bi bi-info-circle text-xl"></i>
        </div>
        <div>
            <h3 class="font-semibold text-blue-900 dark:text-blue-300">New Agent Registrations</h3>
            <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                Users below have registered as agents and are waiting for approval. Review their information and either approve or reject each application.
            </p>
        </div>
    </div>
</div>
@endif

<!-- FILTER BAR -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
    <div class="p-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search by name, email, or phone..." 
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500">
                </div>
            </div>
            
            <div>
                <button id="resetFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition text-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Status Message -->
<div id="statusMessage" class="hidden mb-6 rounded-xl p-3 text-sm"></div>

<!-- Pending Agents Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Registration Requests</h3>
    </div>
    
    <div class="overflow-x-auto">
        <div class="min-w-[1000px]">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Registered</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[300px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pendingAgents as $agent)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white text-base font-bold shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $agent->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $agent->email }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $agent->phone ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $agent->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                        class="approve-btn px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 rounded-lg text-sm font-medium transition"
                                        data-id="{{ $agent->id }}"
                                        data-name="{{ $agent->name }}"
                                        title="Approve Agent">
                                    <i class="bi bi-check-circle me-1"></i> Approve
                                </button>
                                <button type="button"
                                        class="reject-btn px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800/50 rounded-lg text-sm font-medium transition"
                                        data-id="{{ $agent->id }}"
                                        data-name="{{ $agent->name }}"
                                        title="Reject Agent">
                                    <i class="bi bi-x-circle me-1"></i> Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <i class="bi bi-check-circle text-3xl text-green-600 dark:text-green-400"></i>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">No pending agent approvals</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500">All agent registrations have been reviewed</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($pendingAgents->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $pendingAgents->links() }}
    </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Approve Agent Registration</h3>
            <button type="button" class="close-modal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <form id="approveForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="approveUserId" name="user_id">
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Agent Name</label>
                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white" id="agentNameDisplay"></div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Department <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="department" 
                       placeholder="e.g., IT Support, Help Desk" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Position <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="position" 
                       placeholder="e.g., Support Agent, Senior Agent" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500"
                       required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Specialization</label>
                <input type="text" 
                       name="specialization" 
                       placeholder="e.g., Hardware Support, Network Administration"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500">
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Separate multiple specializations with commas.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Skills</label>
                <textarea name="skills" 
                          placeholder="e.g., Troubleshooting, Customer Service, Windows Administration" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 text-sm text-blue-700 dark:text-blue-400">
                <i class="bi bi-info-circle me-2"></i>
                An employee ID will be auto-generated and the agent will receive an approval email.
            </div>
            
            <div class="flex gap-3 justify-end pt-4">
                <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                    <i class="bi bi-check-circle me-1"></i> Approve Agent
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reject Agent Registration</h3>
            <button type="button" class="close-modal text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <form id="rejectForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="rejectUserId" name="user_id">
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Agent Name</label>
                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white" id="rejectAgentNameDisplay"></div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" 
                          placeholder="Provide a brief reason for rejecting this application..." 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500"
                          required></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This reason will be included in the rejection email sent to the applicant.</p>
            </div>
            
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3 text-sm text-red-700 dark:text-red-400">
                <i class="bi bi-exclamation-triangle me-2"></i>
                This action will delete the user account and send a rejection email.
            </div>
            
            <div class="flex gap-3 justify-end pt-4">
                <button type="button" class="close-modal px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                    <i class="bi bi-x-circle me-1"></i> Reject Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    const approveForm = document.getElementById('approveForm');
    const rejectForm = document.getElementById('rejectForm');
    const statusMessage = document.getElementById('statusMessage');
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetFilters');
    
    function showStatusMessage(type, message) {
        if (!statusMessage) return;
        statusMessage.textContent = message;
        statusMessage.className = 'mb-6 rounded-xl p-3 text-sm border';
        if (type === 'success') {
            statusMessage.classList.add('bg-green-50', 'text-green-700', 'border-green-200', 'dark:bg-green-900/20', 'dark:text-green-400', 'dark:border-green-800');
        } else {
            statusMessage.classList.add('bg-red-50', 'text-red-700', 'border-red-200', 'dark:bg-red-900/20', 'dark:text-red-400', 'dark:border-red-800');
        }
        statusMessage.classList.remove('hidden');
        clearTimeout(showStatusMessage.timer);
        showStatusMessage.timer = setTimeout(() => {
            statusMessage.classList.add('hidden');
        }, 5000);
    }
    
    // Approve button handlers
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const agentName = this.dataset.name;
            
            document.getElementById('approveUserId').value = userId;
            document.getElementById('agentNameDisplay').textContent = agentName;
            approveModal.classList.remove('hidden');
        });
    });
    
    // Reject button handlers
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const agentName = this.dataset.name;
            
            document.getElementById('rejectUserId').value = userId;
            document.getElementById('rejectAgentNameDisplay').textContent = agentName;
            rejectModal.classList.remove('hidden');
        });
    });
    
    // Close modal handlers
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            approveModal.classList.add('hidden');
            rejectModal.classList.add('hidden');
        });
    });
    
    // Close modals on outside click
    [approveModal, rejectModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
    
    // Handle approve form submission
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userId = document.getElementById('approveUserId').value;
            const formData = new FormData(this);
            
            fetch(`/admin/users/${userId}/approve-pending-agent`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusMessage('success', data.message || 'Agent approved successfully!');
                    approveModal.classList.add('hidden');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showStatusMessage('error', data.message || 'Error approving agent.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusMessage('error', 'An error occurred while approving the agent.');
            });
        });
    }
    
    // Handle reject form submission
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userId = document.getElementById('rejectUserId').value;
            const formData = new FormData(this);
            
            fetch(`/admin/users/${userId}/reject-pending-agent`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusMessage('success', data.message || 'Application rejected successfully!');
                    rejectModal.classList.add('hidden');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showStatusMessage('error', data.message || 'Error rejecting application.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusMessage('error', 'An error occurred while rejecting the application.');
            });
        });
    }
    
    // Search functionality
    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const search = this.value;
                const url = new URL(window.location);
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }
                window.location = url.toString();
            }, 500);
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location = window.location.pathname;
        });
    }
});
</script>
@endsection
