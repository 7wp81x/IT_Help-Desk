@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Rating
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="{{ route('user.ratings') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Back to Ratings
            </a>
        </div>

        <!-- Edit Rating Card -->
        <div class="bg-white rounded-xl shadow p-8">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Update Your Rating</h3>
                <p class="text-gray-600">
                    Agent: <span class="font-semibold">{{ $rating->agent->name ?? 'Unknown' }}</span>
                    | Ticket: <span class="font-semibold">{{ $rating->ticket->ticket_number ?? 'N/A' }}</span>
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    <i class="bi bi-clock"></i>
                    Original rating: {{ $rating->created_at->diffForHumans() }}
                </p>
            </div>

            <form method="POST" action="{{ route('user.ratings.update', $rating) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Rating Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Your Rating</label>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}"
                                   {{ $rating->rating == $i ? 'checked' : '' }}
                                   class="sr-only rating-input"
                                   onchange="updateStars()">
                            <label for="rating-{{ $i }}" class="cursor-pointer">
                                <i class="bi bi-star text-3xl text-gray-300 transition-colors duration-200 star-icon" 
                                   id="star-{{ $i }}"></i>
                            </label>
                        @endfor
                    </div>
                    <div class="mt-2">
                        <span id="rating-text" class="text-lg font-semibold text-gray-900"></span>
                    </div>
                    @error('rating')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Additional Comments (Optional)</label>
                    <textarea name="comment" id="comment" rows="5" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Share your experience with this agent...">{{ old('comment', $rating->comment) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                    @error('comment')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating Descriptions -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-3">Rating Guide:</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><strong class="text-red-600">1 Star:</strong> Poor - Not helpful, unprofessional</li>
                        <li><strong class="text-orange-600">2 Stars:</strong> Below Average - Somewhat helpful</li>
                        <li><strong class="text-yellow-600">3 Stars:</strong> Average - Adequate service</li>
                        <li><strong class="text-lime-600">4 Stars:</strong> Good - Very helpful and professional</li>
                        <li><strong class="text-green-600">5 Stars:</strong> Excellent - Exceptional service</li>
                    </ul>
                </div>

                <!-- Info Message -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="bi bi-info-circle mr-2"></i>
                        You can edit this rating within <strong>7 days</strong> of submission.
                        This rating was created {{ $rating->created_at->diffForHumans() }}.
                    </p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('user.ratings') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Update Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStars() {
    const rating = document.querySelector('input[name="rating"]:checked')?.value || 0;
    
    // Update stars
    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById('star-' + i);
        if (i <= rating) {
            star.classList.remove('bi-star', 'text-gray-300');
            star.classList.add('bi-star-fill', 'text-yellow-400');
        } else {
            star.classList.remove('bi-star-fill', 'text-yellow-400');
            star.classList.add('bi-star', 'text-gray-300');
        }
    }
    
    // Update text
    const ratingTexts = {
        1: 'Poor - Not helpful, unprofessional',
        2: 'Below Average - Somewhat helpful',
        3: 'Average - Adequate service',
        4: 'Good - Very helpful and professional',
        5: 'Excellent - Exceptional service'
    };
    
    const ratingText = document.getElementById('rating-text');
    ratingText.textContent = rating ? rating + '/5 - ' + ratingTexts[rating] : '';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateStars);
</script>

<style>
.star-icon {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.star-icon:hover,
.star-icon:hover ~ .star-icon {
    transform: scale(1.2);
}
</style>
@endsection
