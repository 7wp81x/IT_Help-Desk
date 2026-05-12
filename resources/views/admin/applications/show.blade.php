@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8" style="margin-left: 0 !important;">
    <div class="space-y-6">
            @if(session('success'))
                <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-700 dark:bg-green-900/40 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-700 dark:bg-red-900/40 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-700 dark:bg-red-900/40 dark:text-red-200">
                    <strong class="font-semibold">There were some problems with this approval request:</strong>
                    <ul class="mt-2 space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $application->full_name }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Application submitted on {{ $application->created_at->format('F j, Y') }}</p>
                    @if($application->user)
                        <p class="mt-2 text-sm font-medium text-indigo-600 dark:text-indigo-300">Registered pending agent found: {{ $application->user->name }} ({{ $application->user->employee_id }})</p>
                    @endif
                </div>
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-sm font-semibold {{ $application->status === 'approved' ? 'border-green-200 bg-green-100 text-green-800 dark:border-green-700 dark:bg-green-900/40 dark:text-green-200' : ($application->status === 'rejected' ? 'border-red-200 bg-red-100 text-red-800 dark:border-red-700 dark:bg-red-900/40 dark:text-red-200' : 'border-yellow-200 bg-yellow-100 text-yellow-800 dark:border-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-200') }}">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2 space-y-6">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Applicant information</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Full name</p>
                            <p class="mt-2 text-base text-gray-900 dark:text-gray-100">{{ $application->full_name }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Email</p>
                            <p class="mt-2 text-base text-gray-900 dark:text-gray-100">{{ $application->email }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Phone</p>
                            <p class="mt-2 text-base text-gray-900 dark:text-gray-100">{{ $application->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Certifications</p>
                            <p class="mt-2 text-base text-gray-900 dark:text-gray-100">{{ $application->certifications_list ?: 'None selected' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Cover letter</h2>
                    <div class="mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm leading-7 text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                        {{ $application->cover_letter }}
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Resume</h2>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Download the applicant resume for review.</p>
                    <a href="{{ Storage::disk('public')->url($application->resume_path) }}" target="_blank" rel="noreferrer" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition duration-150 hover:bg-blue-700">
                        <i class="fas fa-download"></i> Download resume
                    </a>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Admin notes</h2>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Notes are stored with the application record after review.</p>
                    <div class="mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                        {{ $application->admin_notes ?? 'No notes yet.' }}
                    </div>
                </div>

                <div x-data="{
                            openApprove: false,
                            openReject: false,
                            selectedDepartment: '{{ old('department', '') }}',
                            generatedId: '',
                            generateId() {
                                let department = this.selectedDepartment || 'GEN';
                                let code = department.replace(/[^A-Z0-9]/gi, '').toUpperCase().slice(0, 4) || 'GEN';
                                let suffix = Math.random().toString(36).slice(2, 6).toUpperCase();
                                this.generatedId = code === 'GEN' ? `AGT-${suffix}` : `AGT-${code}-${suffix}`;
                            }
                        }"
                        x-init="generateId()"
                        class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Review actions</h2>
                    <div class="mt-4 grid gap-3">
                        <button type="button" @click="openApprove = true; generateId()" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-green-600 px-4 py-3 text-sm font-semibold text-white transition duration-150 hover:bg-green-700">
                            <i class="fas fa-check"></i> Approve application
                        </button>
                        <button type="button" @click="openReject = true" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-red-600 px-4 py-3 text-sm font-semibold text-white transition duration-150 hover:bg-red-700">
                            <i class="fas fa-times"></i> Reject application
                        </button>
                    </div>

                    <!-- APPROVE MODAL - FIXED SIZE WITH SCROLLING -->
                    <div x-show="openApprove" 
                         x-cloak 
                         x-transition.opacity 
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                         style="backdrop-filter: blur(4px);">
                        <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white shadow-2xl dark:bg-gray-900">
                            <!-- Sticky Header -->
                            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Approve Application</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review and create agent account for {{ $application->full_name }}</p>
                                </div>
                                <button type="button" @click="openApprove = false" class="rounded-full p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                            
                            <!-- Scrollable Content -->
                            <form method="POST" action="{{ route('admin.applications.approve', $application) }}" class="p-6 pt-4">
                                @csrf
                                <div class="space-y-5">
                                    <!-- Info Alert -->
                                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/50 dark:bg-blue-950/50 dark:text-blue-200">
                                        <div class="flex gap-2">
                                            <i class="fas fa-info-circle mt-0.5"></i>
                                            <div>
                                                <p class="font-medium">Agent ID will be generated automatically</p>
                                                <p class="mt-1 text-xs opacity-90">Based on the selected department. If not provided, a generic agent ID format will be used.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Employee ID (Auto-generated) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Employee ID *</label>
                                        <div class="relative">
                                            <input name="employee_id" 
                                                   x-model="generatedId" 
                                                   readonly 
                                                   class="w-full rounded-2xl border border-gray-300 bg-gray-100 px-4 py-3 font-mono text-sm text-gray-900 shadow-sm outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                                <i class="fas fa-sync-alt text-gray-400"></i>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated, cannot be edited</p>
                                    </div>

                                    <div class="grid gap-5 sm:grid-cols-2">
                                        <!-- Department -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Department *</label>
                                            <select name="department" 
                                                    x-model="selectedDepartment" 
                                                    @change="generateId()" 
                                                    required
                                                    class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                                <option value="">Select Department</option>
                                                <option value="Technical Support">Technical Support</option>
                                                <option value="Customer Support">Customer Support</option>
                                                <option value="Network Support">Network Support</option>
                                                <option value="Software Support">Software Support</option>
                                                <option value="Hardware Support">Hardware Support</option>
                                            </select>
                                        </div>

                                        <!-- Position -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Position *</label>
                                            <select name="position" 
                                                    required
                                                    class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                                <option value="">Select Position</option>
                                                <option value="Junior Support Agent">Junior Support Agent</option>
                                                <option value="Support Agent">Support Agent</option>
                                                <option value="Senior Support Agent">Senior Support Agent</option>
                                                <option value="Team Lead">Team Lead</option>
                                                <option value="Support Manager">Support Manager</option>
                                            </select>
                                        </div>

                                        <!-- Specialization -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Specialization</label>
                                            <select name="specialization" 
                                                    class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                                <option value="">Select Specialization</option>
                                                <option value="Hardware">Hardware Issues</option>
                                                <option value="Software">Software Issues</option>
                                                <option value="Network">Network Issues</option>
                                                <option value="Database">Database Issues</option>
                                                <option value="Security">Security Issues</option>
                                                <option value="Email">Email Issues</option>
                                                <option value="General">General Support</option>
                                            </select>
                                        </div>

                                        <!-- Skills -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Skills</label>
                                            <input name="skills" 
                                                   type="text" 
                                                   value="{{ old('skills') }}" 
                                                   placeholder="e.g., Networking, Troubleshooting, Customer Service"
                                                   class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                        </div>
                                    </div>

                                    <!-- Admin Notes -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Approval Notes</label>
                                        <textarea name="admin_notes" 
                                                  rows="3" 
                                                  class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" 
                                                  placeholder="Optional notes for the applicant or internal record."></textarea>
                                    </div>
                                </div>

                                <!-- Sticky Footer Buttons -->
                                <div class="sticky bottom-0 mt-6 flex flex-col gap-3 border-t border-gray-200 bg-white pt-5 dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end">
                                    <button type="button" 
                                            @click="openApprove = false" 
                                            class="rounded-full border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 transition duration-150 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="rounded-full bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition duration-150 hover:bg-green-700">
                                        <i class="fas fa-check-circle mr-2"></i> Approve & Create Agent
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- REJECT MODAL - FIXED SIZE WITH SCROLLING -->
                    <div x-show="openReject" 
                         x-cloak 
                         x-transition.opacity 
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                         style="backdrop-filter: blur(4px);">
                        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white shadow-2xl dark:bg-gray-900">
                            <!-- Sticky Header -->
                            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Reject Application</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Provide a reason for rejecting {{ $application->full_name }}'s application</p>
                                </div>
                                <button type="button" @click="openReject = false" class="rounded-full p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                            
                            <!-- Scrollable Content -->
                            <form method="POST" action="{{ route('admin.applications.reject', $application) }}" class="p-6 pt-4">
                                @csrf
                                <div class="space-y-5">
                                    <!-- Warning Alert -->
                                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/50 dark:text-red-200">
                                        <div class="flex gap-2">
                                            <i class="fas fa-exclamation-triangle mt-0.5"></i>
                                            <div>
                                                <p class="font-medium">Confirm Rejection</p>
                                                <p class="mt-1 text-xs opacity-90">This action cannot be undone. The applicant will be notified via email.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rejection Reason -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Rejection Reason *</label>
                                        <textarea name="admin_notes" 
                                                  rows="5" 
                                                  required
                                                  class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" 
                                                  placeholder="Please provide a clear reason for rejection..."></textarea>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This will be included in the rejection email sent to the applicant</p>
                                    </div>
                                </div>

                                <!-- Sticky Footer Buttons -->
                                <div class="sticky bottom-0 mt-6 flex flex-col gap-3 border-t border-gray-200 bg-white pt-5 dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end">
                                    <button type="button" 
                                            @click="openReject = false" 
                                            class="rounded-full border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 transition duration-150 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white transition duration-150 hover:bg-red-700">
                                        <i class="fas fa-times-circle mr-2"></i> Confirm Rejection
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Alpine.js CDN (if not already included) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Custom scrollbar for modals */
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    @media (prefers-color-scheme: dark) {
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #374151;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
    }
    
    /* Smooth backdrop transitions */
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection