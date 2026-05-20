<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl max-w-3xl w-full overflow-hidden" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <h3 id="imagePreviewTitle" class="text-lg font-semibold text-gray-900 dark:text-white flex-1">Image Preview</h3>
            <button type="button" onclick="closeImagePreview()" class="ml-4 flex-shrink-0 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all" title="Close (ESC)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="bg-gray-900 p-4 flex items-center justify-center">
            <img id="imagePreviewContent" src="" alt="Preview" class="max-w-full max-h-96 rounded">
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">Press ESC to close</p>
            <div class="flex gap-2">
                <button type="button" onclick="closeImagePreview()" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all shadow-sm hover:shadow-md">✕ Close</button>
                <a id="imageDownloadLink" href="#" download class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-all shadow-sm hover:shadow-md">⬇ Download</a>
            </div>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div id="attachmentPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4" onclick="closeAttachmentPreview()">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-5xl h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                <h3 id="attachmentPreviewTitle" class="text-lg font-semibold text-gray-900 dark:text-white flex-1">Document Preview</h3>
                <button type="button" onclick="closeAttachmentPreview()" class="ml-4 flex-shrink-0 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all" title="Close (ESC)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <iframe id="attachmentPreviewFrame" class="w-full h-[calc(100%-112px)] bg-white dark:bg-gray-900" src="" frameborder="0"></iframe>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-900">
                <p class="text-xs text-gray-500 dark:text-gray-400">Press ESC to close</p>
                <button type="button" onclick="closeAttachmentPreview()" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all shadow-sm hover:shadow-md">
                    ✕ Close
                </button>
            </div>
        </div>
    </div>
</div>