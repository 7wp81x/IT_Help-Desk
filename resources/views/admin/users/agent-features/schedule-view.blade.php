@extends('layouts.app')

@section('title', 'Shift Schedule')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Shift Schedule</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View your work schedule and shifts</p>
        </div>
        <div class="flex gap-3">
            <button onclick="previousWeek()" class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                <i class="bi bi-chevron-left"></i> Previous
            </button>
            <button onclick="currentWeek()" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Current Week
            </button>
            <button onclick="nextWeek()" class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                Next <i class="bi bi-chevron-right"></i>
            </button>
            <a href="{{ route('admin.users.agents') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Week Selector -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="text-center">
            <p class="text-lg font-semibold text-gray-900" id="weekRange">{{ $weekRange }}</p>
        </div>
    </div>

    <!-- Schedule Grid -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 text-center">
                <p class="font-semibold text-gray-900">{{ $day }}</p>
                <p class="text-xs text-gray-500">{{ $dates[loop->index] ?? '' }}</p>
            </div>
            @endforeach
        </div>
        
        <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
            @foreach($schedule as $day)
            <div class="bg-white dark:bg-gray-800 p-3 min-h-[150px]">
                @if($day['shifts'])
                    @foreach($day['shifts'] as $shift)
                    <div class="mb-2 p-2 rounded-lg {{ $shift['type'] == 'morning' ? 'bg-blue-100 dark:bg-blue-900/30' : ($shift['type'] == 'afternoon' ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-gray-100 dark:bg-gray-700') }}">
                        <p class="text-sm font-medium">{{ $shift['start'] }} - {{ $shift['end'] }}</p>
                        <p class="text-xs text-gray-600">{{ $shift['title'] }}</p>
                        @if($shift['is_today'])
                            <span class="inline-block mt-1 px-1.5 py-0.5 text-xs bg-green-500 text-white rounded">Today</span>
                        @endif
                    </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-400 text-center mt-4">—</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Shift Legend -->
    <div class="mt-6 flex flex-wrap gap-4 justify-center">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-blue-100"></div>
            <span class="text-sm text-gray-600">Morning Shift (8:00 - 16:00)</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-yellow-100"></div>
            <span class="text-sm text-gray-600">Afternoon Shift (14:00 - 22:00)</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-gray-100"></div>
            <span class="text-sm text-gray-600">Night Shift (22:00 - 6:00)</span>
        </div>
    </div>
</div>

<script>
let currentDate = new Date();

function getWeekRange(date) {
    const monday = new Date(date);
    monday.setDate(monday.getDate() - (monday.getDay() + 6) % 7);
    const sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    return `${monday.toLocaleDateString()} - ${sunday.toLocaleDateString()}`;
}

function previousWeek() {
    currentDate.setDate(currentDate.getDate() - 7);
    loadSchedule();
}

function nextWeek() {
    currentDate.setDate(currentDate.getDate() + 7);
    loadSchedule();
}

function currentWeek() {
    currentDate = new Date();
    loadSchedule();
}

function loadSchedule() {
    fetch(`{{ route('admin.users.agents.schedule') }}?week=${currentDate.toISOString()}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('weekRange').textContent = data.weekRange;
            location.reload();
        });
}
</script>
@endsection