@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Department</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update department information</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            @php
                $oldSpecs = old('specializations', $department->specializations ?? []);
                $specCount = count($oldSpecs) > 0 ? count($oldSpecs) : 1;
            @endphp

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Name *</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $department->name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('description', $department->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex items-center justify-between mb-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specializations</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Add specializations that define agent expertise within this department.</p>
                        </div>
                        <button type="button"
                                id="addSpecializationBtn"
                                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg hover:bg-indigo-200 transition">
                            <i class="bi bi-plus-circle"></i>
                            Add Specialization
                        </button>
                    </div>

                    <div id="specializationsContainer" class="space-y-3">
                        @for($i = 0; $i < $specCount; $i++)
                            <div class="specialization-item flex flex-col gap-2 sm:flex-row sm:items-start">
                                <div class="flex-1">
                                    <label class="sr-only" for="specialization-name-{{ $i }}">Specialization Name</label>
                                    <input type="text"
                                           id="specialization-name-{{ $i }}"
                                           name="specializations[{{ $i }}][name]"
                                           value="{{ $oldSpecs[$i]['name'] ?? '' }}"
                                           placeholder="e.g., Hardware Support"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="button"
                                        class="remove-spec-btn mt-1 sm:mt-0 w-full sm:w-12 h-11 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 transition flex items-center justify-center {{ $i === 0 && $specCount === 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $i === 0 && $specCount === 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-trash text-base"></i>
                                </button>
                            </div>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $department->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.departments.index') }}"
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                    Update Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('specializationsContainer');
    const addBtn = document.getElementById('addSpecializationBtn');
    let specCount = {{ max($specCount, 1) }};

    function updateRemoveButtons() {
        const removeBtns = container.querySelectorAll('.remove-spec-btn');
        removeBtns.forEach((btn, index) => {
            if (index === 0 && removeBtns.length === 1) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    function reindexSpecializations() {
        const items = container.querySelectorAll('.specialization-item');
        specCount = items.length;

        items.forEach((item, newIndex) => {
            const nameInput = item.querySelector('input[name^="specializations"]');
            if (nameInput) {
                nameInput.name = `specializations[${newIndex}][name]`;
                nameInput.id = `specialization-name-${newIndex}`;
            }
        });
    }

    addBtn.addEventListener('click', function() {
        const newIndex = specCount;
        const newItem = document.createElement('div');
        newItem.className = 'specialization-item flex flex-col gap-2 sm:flex-row sm:items-start';
        newItem.innerHTML = `
            <div class="flex-1">
                <label class="sr-only" for="specialization-name-${newIndex}">Specialization Name</label>
                <input type="text"
                       id="specialization-name-${newIndex}"
                       name="specializations[${newIndex}][name]"
                       placeholder="e.g., Hardware Support"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="button"
                    class="remove-spec-btn mt-1 sm:mt-0 w-full sm:w-12 h-11 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 transition flex items-center justify-center">
                <i class="bi bi-trash text-base"></i>
            </button>
        `;

        container.appendChild(newItem);
        specCount++;
        updateRemoveButtons();

        const removeBtn = newItem.querySelector('.remove-spec-btn');
        removeBtn.addEventListener('click', function() {
            if (!removeBtn.disabled) {
                newItem.remove();
                reindexSpecializations();
                updateRemoveButtons();
            }
        });
    });

    container.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-spec-btn');
        if (removeBtn && !removeBtn.disabled) {
            const item = removeBtn.closest('.specialization-item');
            if (item) {
                item.remove();
                reindexSpecializations();
                updateRemoveButtons();
            }
        }
    });

    updateRemoveButtons();

    const existingRemoveBtns = container.querySelectorAll('.remove-spec-btn');
    existingRemoveBtns.forEach(btn => {
        if (!btn.disabled) {
            btn.addEventListener('click', function() {
                const item = btn.closest('.specialization-item');
                if (item) {
                    item.remove();
                    reindexSpecializations();
                    updateRemoveButtons();
                }
            });
        }
    });
});
</script>
@endpush