@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Announcements
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-cyan-100 rounded-full text-cyan-600">
                        <i class="bi bi-megaphone-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Announcements</p>
                        <p class="text-2xl font-bold">{{ $announcements->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                        <i class="bi bi-bell-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Latest Updates</p>
                        <p class="text-2xl font-bold">{{ $announcements->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select name="read_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Announcements</option>
                        <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                    Apply
                </button>
            </form>
        </div>

        <!-- Announcements List -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Announcements</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($announcements as $announcement)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-150 border-l-4 {{ $announcement->isReadBy(auth()->user()) ? 'border-gray-200' : 'border-blue-600' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('user.announcements.show', $announcement) }}" class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                                        {{ $announcement->title }}
                                    </a>
                                    @if(!$announcement->isReadBy(auth()->user()))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="bi bi-calendar-event mr-1"></i>
                                    {{ $announcement->published_at->format('M d, Y') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-cyan-100 text-cyan-700 px-3 py-1 text-xs font-medium">
                                {{ $announcement->priority ?? 'Normal' }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $announcement->content }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('user.announcements.show', $announcement) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Read More →
                            </a>
                            <p class="text-xs text-gray-500">
                                {{ $announcement->isReadBy(auth()->user()) ? 'Read' : 'Unread' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="bi bi-megaphone text-4xl text-gray-300 block mb-4"></i>
                        <p class="text-gray-500 text-lg">No announcements at this time.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($announcements->hasPages())
            <div class="mt-8">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
