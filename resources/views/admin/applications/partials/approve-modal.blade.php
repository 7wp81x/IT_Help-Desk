<!-- Approve Modal -->
<div id="approveModal" class="modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
     style="backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-xl bg-white shadow-2xl dark:bg-gray-900">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-900">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Approve Application</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review and create agent account for {{ $application->full_name }}</p>
            </div>
            <button type="button" onclick="closeModal('approveModal')" 
                    class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.applications.approve', $application) }}" class="p-6" id="approveForm">
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

                    <!-- Categories Section (replacing Specialization) -->
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

            <div class="sticky bottom-0 mt-6 flex flex-col gap-3 border-t border-gray-200 bg-white pt-5 dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('approveModal')" 
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:from-green-700 hover:to-emerald-700">
                    <i class="bi bi-check-circle mr-2"></i> Approve & Create Agent
                </button>
            </div>
        </form>
    </div>
</div>
