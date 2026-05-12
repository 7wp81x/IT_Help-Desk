@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Category Report
        </h2>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">Back to Reports</a>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Category</th>
                                <th class="text-left">Description</th>
                                <th class="text-center">Total Tickets</th>
                                <th class="text-center">Open</th>
                                <th class="text-center">Resolved</th>
                                <th class="text-center">Priority Level</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                @php
                                    $icon = $category->icon ?? 'fa-tag';
                                    $color = $category->color ?? '#6B7280';
                                    $description = $category->description ?? 'No description';
                                    $openCount = $category->open_count ?? 0;
                                    $resolvedCount = $category->resolved_count ?? 0;
                                    $priorityLevel = $category->priority_level ?? 1;
                                    $isActive = $category->is_active ?? false;
                                @endphp
                                <tr class="border-b">
                                    <td class="py-2">
                                        <div class="flex items-center space-x-2">
                                            <i class="bi {{ $icon }} {{ $colorClass }}"></i>
                                            <span class="font-medium">{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($description, 50) }}</td>
                                    <td class="text-center font-bold">{{ $category->tickets_count }}</td>
                                    <td class="text-center text-yellow-600">{{ $openCount }}</td>
                                    <td class="text-center text-green-600">{{ $resolvedCount }}</td>
                                    <td class="text-center">
                                        @if($priorityLevel >= 4)
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Level {{ $priorityLevel }}</span>
                                        @elseif($priorityLevel >= 2)
                                            <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Level {{ $priorityLevel }}</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Level {{ $priorityLevel }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($isActive)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No categories found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection