@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My Ratings
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                        <i class="bi bi-star-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Ratings</p>
                        <p class="text-2xl font-bold">{{ $ratings->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        <i class="bi bi-hand-thumbs-up-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">5 Star Ratings</p>
                        <p class="text-2xl font-bold">{{ $ratings->total() ? $ratings->where('rating', 5)->count() : '0' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600">
                        <i class="bi bi-chat-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">With Comments</p>
                        <p class="text-2xl font-bold">{{ $ratings->total() ? $ratings->whereNotNull('comment')->count() : '0' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <i class="bi bi-bar-chart text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Average Rating</p>
                        <p class="text-2xl font-bold">
                            @if($ratings->total())
                                {{ number_format($ratings->avg('rating'), 1) }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <form method="GET" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Rating</label>
                    <select name="rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Ratings</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Most Recent</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="rating_high" {{ request('sort') === 'rating_high' ? 'selected' : '' }}>Highest Rating</option>
                        <option value="rating_low" {{ request('sort') === 'rating_low' ? 'selected' : '' }}>Lowest Rating</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                    Apply
                </button>
            </form>
        </div>

        <!-- Ratings List -->
        <div class="space-y-4">
            @forelse($ratings as $rating)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <!-- Agent & Ticket Info -->
                            <div class="mb-4">
                                <p class="font-semibold text-gray-900">{{ $rating->agent->name ?? 'Unknown Agent' }}</p>
                                <p class="text-sm text-gray-600">
                                    <i class="bi bi-ticket"></i> 
                                    {{ $rating->ticket->ticket_number ?? 'N/A' }} - {{ $rating->ticket->title ?? 'N/A' }}
                                </p>
                            </div>

                            <!-- Rating Stars and Comment -->
                            <div class="mb-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }} text-yellow-400"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $rating->rating }}/5</span>
                                </div>
                                @if($rating->comment)
                                    <p class="text-sm text-gray-600">{{ $rating->comment }}</p>
                                @endif
                            </div>

                            <!-- Metadata -->
                            <p class="text-xs text-gray-500">
                                Rated {{ $rating->created_at->diffForHumans() }}
                                @if($rating->created_at->diffInDays(now()) <= 7)
                                    • <span class="text-blue-600">Editable</span>
                                @endif
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            @if($rating->created_at->diffInDays(now()) <= 7)
                                <a href="{{ route('user.ratings.edit', $rating) }}" 
                                   class="px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition duration-200 text-sm font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('user.ratings.destroy', $rating) }}" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this rating?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition duration-200 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-500 px-2">Cannot edit (7 days passed)</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-12 text-center">
                    <i class="bi bi-star text-4xl text-gray-300 block mb-4"></i>
                    <p class="text-gray-500 text-lg mb-4">No ratings submitted yet.</p>
                    <p class="text-gray-500 mb-6">You can rate agents after your tickets are resolved.</p>
                    <a href="{{ route('user.tickets.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        View My Tickets
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($ratings->hasPages())
            <div class="mt-8">
                {{ $ratings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
