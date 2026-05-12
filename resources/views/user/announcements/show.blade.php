@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $announcement->title }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="{{ route('user.announcements') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Back to Announcements
            </a>
        </div>

        <!-- Announcement Card -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 p-8 text-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $announcement->title }}</h1>
                        <p class="text-cyan-100">
                            <i class="bi bi-calendar-event mr-2"></i>
                            {{ $announcement->published_at->format('F d, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <span class="px-4 py-2 bg-white bg-opacity-20 rounded-lg text-sm font-medium">
                        {{ $announcement->priority ?? 'Normal' }} Priority
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="prose prose-sm max-w-none text-gray-700 mb-8">
                    {!! nl2br(e($announcement->content)) !!}
                </div>

                <!-- Meta Information -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Published By</p>
                            <p class="font-medium text-gray-900">{{ $announcement->author->name ?? 'Administration' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Announcement Type</p>
                            <p class="font-medium text-gray-900">{{ $announcement->type ?? 'General' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <p class="font-medium">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    Active
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-gray-200 mt-6 pt-6 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">
                            @if($announcement->isReadBy(auth()->user()))
                                <i class="bi bi-check-circle-fill text-green-600 mr-2"></i> Marked as read
                            @else
                                <i class="bi bi-circle mr-2"></i> Not read yet
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 flex items-center gap-2">
                            <i class="bi bi-bookmark"></i> Save
                        </button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 flex items-center gap-2">
                            <i class="bi bi-share"></i> Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Announcements -->
        @if(isset($relatedAnnouncements) && $relatedAnnouncements->isNotEmpty())
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Announcements</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedAnnouncements as $related)
                        <a href="{{ route('user.announcements.show', $related) }}" class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 p-6 block">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $related->title }}</h4>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $related->content }}</p>
                            <p class="text-xs text-gray-500">{{ $related->published_at->format('M d, Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
