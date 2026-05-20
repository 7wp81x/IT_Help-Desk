<div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-b-lg">
    <form action="{{ route('user.tickets.comment', $ticket) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add Comment</label>
        <textarea name="content" rows="3" required 
                  class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Type your comment here..."></textarea>
        <div class="mt-3">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Attachments</label>
            <input type="file" name="attachments[]" multiple 
                   class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-600 dark:file:text-gray-200"
                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
        </div>
        <div class="flex justify-end mt-3">
            <button type="submit" class="px-4 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                Send Comment
            </button>
        </div>
    </form>
</div>