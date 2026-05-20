<!-- Reject Modal -->
<div id="rejectModal" class="modal fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
     style="backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-xl bg-white shadow-2xl dark:bg-gray-900">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-900">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Reject Application</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Provide a reason for rejecting {{ $application->full_name }}'s application</p>
            </div>
            <button type="button" onclick="closeModal('rejectModal')" 
                    class="rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.applications.reject', $application) }}" class="p-6">
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

            <div class="sticky bottom-0 mt-6 flex flex-col gap-3 border-t border-gray-200 bg-white pt-5 dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('rejectModal')" 
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:from-red-700 hover:to-rose-700">
                    <i class="bi bi-x-circle mr-2"></i> Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>