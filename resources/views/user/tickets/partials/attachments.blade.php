<div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Attachments:</p>
    <div class="flex flex-wrap gap-3">
        @foreach($attachments as $attachment)
            <a href="{{ route('user.tickets.download', $attachment) }}"
               data-url="{{ route('user.tickets.download', $attachment) }}"
               data-name="{{ $attachment->original_name }}"
               onclick="openAttachmentPreview(event)"
               class="text-blue-600 hover:text-blue-700 text-xs flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                {{ $attachment->original_name }}
            </a>
        @endforeach
    </div>
</div>