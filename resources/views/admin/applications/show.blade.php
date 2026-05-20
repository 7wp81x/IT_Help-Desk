@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header with Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.applications') }}" 
           class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm mb-4">
            <i class="bi bi-arrow-left"></i>
            Back to Applications
        </a>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Application Details</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Review applicant information and take action</p>
            </div>
            @php
                $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                $statusIcon = 'bi-clock-history';
                if ($application->status === 'approved') {
                    $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                    $statusIcon = 'bi-check-circle-fill';
                } elseif ($application->status === 'rejected') {
                    $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                    $statusIcon = 'bi-x-circle-fill';
                }
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium {{ $statusClass }} w-fit">
                <i class="bi {{ $statusIcon }}"></i>
                {{ ucfirst($application->status) }}
            </span>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-700 dark:bg-green-900/40 dark:text-green-200">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @elseif(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-700 dark:bg-red-900/40 dark:text-red-200">
            <div class="flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-700 dark:bg-red-900/40 dark:text-red-200">
            <div class="flex items-center gap-2 mb-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong class="font-semibold">There were some problems:</strong>
            </div>
            <ul class="mt-2 space-y-1 list-disc list-inside pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left Side - 2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Applicant Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold shadow-sm">
                            {{ strtoupper(substr($application->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $application->full_name }}</h2>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-calendar3"></i> Submitted on {{ \Carbon\Carbon::parse($application->created_at)->format('F j, Y') }}
                                </p>
                                @if($application->user)
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400">
                                        <i class="bi bi-person-check"></i> Registered Agent: {{ $application->user->name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applicant Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-person-badge text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Applicant Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="bi bi-person"></i> Full Name
                            </p>
                            <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $application->full_name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="bi bi-envelope"></i> Email Address
                            </p>
                            <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                <a href="mailto:{{ $application->email }}" class="text-indigo-600 hover:underline">{{ $application->email }}</a>
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="bi bi-telephone"></i> Phone Number
                            </p>
                            <p class="mt-1 text-base font-medium text-gray-900 dark:text-white">
                                {{ $application->phone ?? 'Not provided' }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                <i class="bi bi-patch-check"></i> Certifications
                            </p>
                            <div class="mt-1 flex flex-wrap gap-1">
                                @php
                                    $certs = explode(',', $application->certifications_list ?? '');
                                @endphp
                                @if(!empty($application->certifications_list))
                                    @foreach($certs as $cert)
                                        @if(trim($cert))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                            {{ trim($cert) }}
                                        </span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">None selected</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resume & Cover Letter Side by Side -->
            @php
                $resumeUrl = $application->resume_path ? Storage::disk('public')->url($application->resume_path) : null;
                $resumeExt = $application->resume_path ? strtolower(pathinfo($application->resume_path, PATHINFO_EXTENSION)) : null;
                $coverLetterUrl = $application->cover_letter_path ? Storage::disk('public')->url($application->cover_letter_path) : null;
                $coverLetterExt = $application->cover_letter_path ? strtolower(pathinfo($application->cover_letter_path, PATHINFO_EXTENSION)) : null;
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Resume Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-file-earmark-text text-indigo-600 dark:text-indigo-400 text-lg"></i>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Resume / CV</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        @if($resumeUrl)
                            @if($resumeExt === 'pdf')
                                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 mb-4" style="height: 300px;">
                                    <iframe src="{{ route('admin.applications.view', ['application' => $application->id, 'fileType' => 'resume']) }}" class="w-full h-full bg-white" frameborder="0"></iframe>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 text-center mb-4" style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-earmark text-5xl text-gray-400"></i>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ strtoupper($resumeExt) }} file uploaded</p>
                                </div>
                            @endif
                            
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('admin.applications.view', ['application' => $application->id, 'fileType' => 'resume']) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-lg transition font-medium text-sm">
                                    <i class="bi bi-eye"></i> View Resume
                                </a>
                                <a href="{{ route('admin.applications.download', ['application' => $application->id, 'fileType' => 'resume']) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="bi bi-download"></i> Download Resume
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8" style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content; center;">
                                <i class="bi bi-file-earmark-x text-5xl text-gray-400"></i>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No resume uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cover Letter Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-envelope-paper text-indigo-600 dark:text-indigo-400 text-lg"></i>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Cover Letter</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        @if($coverLetterUrl)
                            @if($coverLetterExt === 'pdf')
                                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 mb-4" style="height: 300px;">
                                    <iframe src="{{ route('admin.applications.view', ['application' => $application->id, 'fileType' => 'cover_letter']) }}" class="w-full h-full bg-white" frameborder="0"></iframe>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 text-center mb-4" style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-text text-5xl text-gray-400"></i>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ strtoupper($coverLetterExt) }} file uploaded</p>
                                </div>
                            @endif
                            
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('admin.applications.view', ['application' => $application->id, 'fileType' => 'cover_letter']) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-lg transition font-medium text-sm">
                                    <i class="bi bi-eye"></i> View Cover Letter
                                </a>
                                <a href="{{ route('admin.applications.download', ['application' => $application->id, 'fileType' => 'cover_letter']) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="bi bi-download"></i> Download Cover Letter
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8" style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content; center;">
                                <i class="bi bi-envelope-paper-x text-5xl text-gray-400"></i>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No cover letter uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right Side - 1/3 width) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Admin Notes Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-journal-text text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Admin Notes</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 text-sm text-gray-700 dark:text-gray-300 min-h-[100px]">
                        {{ $application->admin_notes ?? 'No notes yet.' }}
                    </div>
                </div>
            </div>

            <!-- Approval Credentials Card (Only for approved applications) -->
            @if($application->status === 'approved' && ($application->generated_employee_id || $application->generated_password))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-green-200 dark:border-green-800/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-green-200 dark:border-green-800/50 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-950/30 dark:to-emerald-950/30">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-shield-check text-green-600 dark:text-green-400 text-lg"></i>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Agent Approval Records</h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    @if($application->generated_employee_id)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">
                            <i class="bi bi-person-badge"></i> Generated Employee ID
                        </label>
                        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800/50 rounded-lg p-3 font-mono text-sm text-gray-900 dark:text-white break-all">
                            {{ $application->generated_employee_id }}
                        </div>
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            This is the Employee ID that was generated and sent to the agent during approval.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review Actions Card (Only for pending applications) -->
            @if($application->status === 'pending')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check2-circle text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Review Actions</h3>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    <button type="button" onclick="openModal('approveModal')" 
                            class="inline-flex w-full items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition font-medium text-sm">
                        <i class="bi bi-check-circle"></i>
                        Approve Application
                    </button>
                    <button type="button" onclick="openModal('rejectModal')" 
                            class="inline-flex w-full items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-lg transition font-medium text-sm">
                        <i class="bi bi-x-circle"></i>
                        Reject Application
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ========== APPROVE MODAL ========== -->
<div id="approveModal" class="modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" style="backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-3xl max-h-[90vh] rounded-xl bg-white shadow-2xl dark:bg-gray-900 flex flex-col">
        <!-- Fixed Header -->
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 rounded-t-xl dark:border-gray-700 dark:bg-gray-900">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Approve Application</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review and create agent account for {{ $application->full_name }}</p>
            </div>
            <button type="button" onclick="closeModal('approveModal')" 
                    class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <!-- Scrollable Content -->
        <div class="overflow-y-auto p-6 flex-1">
            <form method="POST" action="{{ route('admin.applications.approve', $application) }}" id="approveForm">
                @csrf
                <div class="space-y-5">
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/50 dark:bg-blue-950/50 dark:text-blue-200">
                        <div class="flex gap-2">
                            <i class="bi bi-info-circle mt-0.5"></i>
                            <div>
                                <p class="font-medium">Agent ID will be generated automatically</p>
                                <p class="mt-1 text-xs opacity-90">Based on the selected department. If not provided, a generic agent ID format will be used.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Employee ID * <span class="text-red-500">(Auto-generated)</span></label>
                        <div class="relative">
                            <input name="employee_id" id="employee_id" readonly required
                                   class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-2.5 font-mono text-sm text-gray-900 shadow-sm outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                                   placeholder="Select department to generate">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                <i class="bi bi-arrow-repeat text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated based on department, cannot be edited. This ID will be sent to the agent.</p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Department *</label>
                            <select name="department_id" id="departmentSelect" required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Position *</label>
                            <select name="position" required
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">Select Position</option>
                                <option value="Junior Support Agent">Junior Support Agent</option>
                                <option value="Support Agent">Support Agent</option>
                                <option value="Senior Support Agent">Senior Support Agent</option>
                                <option value="Team Lead">Team Lead</option>
                                <option value="Support Manager">Support Manager</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Categories</label>
                            <select name="category_ids[]" id="categorySelect" multiple size="5"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">Select a department first</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Select one or more categories. Use Ctrl/Cmd click or Shift click to choose multiple. Only categories from the selected department will appear.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                Approval Notes
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    Optional
                                </span>
                            </label>
                            <textarea name="admin_notes" rows="4" 
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" 
                                    placeholder="Optional notes for the applicant or internal record. This will be visible to the admin only."></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Fixed Footer -->
        <div class="sticky bottom-0 mt-6 flex flex-col gap-3 border-t border-gray-200 bg-white pt-5 pb-5 px-6 rounded-b-xl dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end">
            <button type="button" onclick="closeModal('approveModal')" 
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
            </button>
            <button type="submit" form="approveForm" 
                    class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:from-green-700 hover:to-emerald-700">
                <i class="bi bi-check-circle mr-2"></i> Approve & Create Agent
            </button>
        </div>
    </div>
</div>

<!-- ========== REJECT MODAL ========== -->
<div id="rejectModal" class="modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" style="backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-2xl max-h-[90vh] rounded-xl bg-white shadow-2xl dark:bg-gray-900 flex flex-col">
        <!-- Fixed Header -->
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 rounded-t-xl dark:border-gray-700 dark:bg-gray-900">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Reject Application</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Provide a reason for rejecting {{ $application->full_name }}'s application</p>
            </div>
            <button type="button" onclick="closeModal('rejectModal')" 
                    class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <!-- Scrollable Content -->
        <div class="overflow-y-auto p-6 flex-1">
            <form method="POST" action="{{ route('admin.applications.reject', $application) }}" id="rejectForm">
                @csrf
                <div class="space-y-5">
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/50 dark:text-red-200">
                        <div class="flex gap-2">
                            <i class="bi bi-exclamation-triangle mt-0.5"></i>
                            <div>
                                <p class="font-medium">Confirm Rejection</p>
                                <p class="mt-1 text-xs opacity-90">This action cannot be undone. The applicant will be notified via email.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Rejection Reason *</label>
                        <textarea name="admin_notes" rows="5" required
                                  class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-900 shadow-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" 
                                  placeholder="Please provide a clear reason for rejection..."></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This will be included in the rejection email sent to the applicant</p>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Fixed Footer -->
        <div class="sticky bottom-0 mt-6 flex flex-col gap-3 border-t border-gray-200 bg-white pt-5 pb-5 px-6 rounded-b-xl dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end">
            <button type="button" onclick="closeModal('rejectModal')" 
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                Cancel
            </button>
            <button type="submit" form="rejectForm" 
                    class="rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:from-red-700 hover:to-rose-700">
                <i class="bi bi-x-circle mr-2"></i> Confirm Rejection
            </button>
        </div>
    </div>
</div>

<style>
    .modal:target {
        display: flex;
    }
    
    .overflow-y-auto {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    
    iframe {
        pointer-events: auto;
        will-change: auto;
    }
    
    .sticky {
        position: sticky;
        top: 0;
    }
    
    /* Better scrollbar styling */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
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
</style>

<script>
function closeModal(modalId) {
    const el = document.getElementById(modalId);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    if (window.location.hash && (window.location.hash === '#approveModal' || window.location.hash === '#approve' || window.location.hash === '#rejectModal' || window.location.hash === '#reject')) {
        history.replaceState('', document.title, window.location.pathname + window.location.search);
    }
}

function openModal(modalId) {
    // First, close ALL open modals
    document.querySelectorAll('.modal').forEach(modal => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });
    
    // Then open the one you want
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }
}

function copyToClipboard(text, elementId) {
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        btn.classList.add('bg-green-100', 'dark:bg-green-900/50');
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-100', 'dark:bg-green-900/50');
        }, 2000);
    }).catch(() => {
        alert('Failed to copy to clipboard');
    });
}

// Dynamic category loading based on department
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('departmentSelect');
    const categorySelect = document.getElementById('categorySelect');
    const approveForm = document.getElementById('approveForm');
    
    if (departmentSelect && categorySelect) {
        departmentSelect.addEventListener('change', function() {
            const departmentId = this.value;
            
            if (!departmentId) {
                categorySelect.innerHTML = '<option value="">Select a department first</option>';
                return;
            }
            
            categorySelect.innerHTML = '<option value="">Loading categories...</option>';
            
            const categoryUrl = `/admin/categories/by-department?department_id=${departmentId}`;
            
            fetch(categoryUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const categories = data.categories || [];
                if (categories.length > 0) {
                    categorySelect.innerHTML = categories.map(category => {
                        return `<option value="${category.id}">${category.name}</option>`;
                    }).join('');
                } else {
                    categorySelect.innerHTML = '<option value="">No categories available for this department</option>';
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                categorySelect.innerHTML = '<option value="">Error loading categories</option>';
            });
            
            const genUrl = `/admin/departments/${departmentId}/generate-employee-id`;
            fetch(genUrl)
                .then(r => r.json())
                .then(d => {
                    if (d.employee_id) {
                        const empEl = document.getElementById('employee_id');
                        if (empEl) empEl.value = d.employee_id;
                    }
                })
                .catch(e => console.error('Could not generate employee id:', e));
        });
    }

    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            const empIdField = document.getElementById('employee_id');
            if (!empIdField || !empIdField.value.trim()) {
                e.preventDefault();
                alert('Please select a department first to generate an Employee ID.');
                return false;
            }
        });
    }
    
    // Open modal from URL hash
    const hash = (window.location.hash || '').replace('#', '');
    if (hash === 'approveModal' || hash === 'approve') {
        openModal('approveModal');
    }
    if (hash === 'rejectModal' || hash === 'reject') {
        openModal('rejectModal');
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
        document.body.style.overflow = '';
    }
});

// Handle Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal.flex');
        openModals.forEach(modal => {
            closeModal(modal.id);
            document.body.style.overflow = '';
        });
    }
});
</script>
@endsection