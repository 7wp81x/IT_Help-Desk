<div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
    <div class="space-y-2">
        @php
            $imageAttachments = $attachments->filter(fn($a) => in_array(strtolower(pathinfo($a->original_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']));
            $documentAttachments = $attachments->reject(fn($a) => in_array(strtolower(pathinfo($a->original_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']));
        @endphp
        
        {{-- Image Previews --}}
        @if($imageAttachments->count() > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($imageAttachments as $attachment)
                    <div class="relative group cursor-pointer" onclick="openImagePreview(event, '{{ route('agent.tickets.download', $attachment) }}', '{{ $attachment->original_name }}')">
                        <img src="{{ route('agent.tickets.download', $attachment) }}" 
                             alt="{{ $attachment->original_name }}"
                             class="max-w-32 max-h-32 rounded border border-gray-300 dark:border-gray-600 hover:opacity-80 transition object-cover">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 rounded transition flex items-center justify-center">
                            <span class="text-white text-xs font-medium opacity-0 group-hover:opacity-100">Preview</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        {{-- Document Attachments --}}
        @if($documentAttachments->count() > 0)
            <div class="space-y-1">
                @foreach($documentAttachments as $attachment)
                    @php
                        $ext = strtolower(pathinfo($attachment->original_name, PATHINFO_EXTENSION));
                        $icons = [
                            'pdf' => 'PDF',
                            'doc' => 'DOC',
                            'docx' => 'DOCX',
                            'txt' => 'TXT',
                            'zip' => 'ZIP',
                        ];
                        $icon = $icons[$ext] ?? 'FILE';
                        $size = number_format($attachment->size / 1024, 2) . ' KB';
                    @endphp
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded px-2 py-1.5 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer" onclick="openAttachmentPreview(event, '{{ route('agent.tickets.download', $attachment) }}', '{{ $attachment->original_name }}')" title="Click to preview">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <span class="text-xs font-mono font-bold text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $icon }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $attachment->original_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $size }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-2 flex gap-1">
                            <button type="button" onclick="event.stopPropagation(); openAttachmentPreview(event, '{{ route('agent.tickets.download', $attachment) }}', '{{ $attachment->original_name }}')" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 p-1" title="Preview">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('agent.tickets.download', $attachment) }}" class="flex-shrink-0 text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 p-1" title="Download">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>