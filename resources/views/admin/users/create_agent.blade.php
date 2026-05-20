@extends('layouts.app')

@section('title', 'Create New Agent')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Support Agent</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add a new support agent to the system</p>
    </div>

    <!-- Info Banner -->
    <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/50 dark:bg-blue-950/50 dark:text-blue-200">
        <div class="flex gap-2">
            <i class="bi bi-info-circle mt-0.5"></i>
            <div>
                <p class="font-medium">Agent ID will be generated automatically</p>
                <p class="mt-1 text-xs opacity-90">Based on the selected department. The Employee ID will be auto-generated and sent to the agent.</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createAgentForm">
            @csrf
            <input type="hidden" name="role" value="agent">

            <!-- Personal Information Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-person-circle mr-2"></i> Personal Information
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Employee ID <span class="text-red-500">*</span> <span class="text-xs text-gray-500">(Auto-generated)</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="employee_id" id="employee_id" readonly
                                   value="{{ old('employee_id') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-mono focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                <i class="bi bi-arrow-repeat text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Auto-generated based on department selection</p>
                        @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Position/Title
                        </label>
                        <select name="position" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Select Position</option>
                            <option value="Junior Support Agent" {{ old('position') == 'Junior Support Agent' ? 'selected' : '' }}>Junior Support Agent</option>
                            <option value="Support Agent" {{ old('position') == 'Support Agent' ? 'selected' : '' }}>Support Agent</option>
                            <option value="Senior Support Agent" {{ old('position') == 'Senior Support Agent' ? 'selected' : '' }}>Senior Support Agent</option>
                            <option value="Team Lead" {{ old('position') == 'Team Lead' ? 'selected' : '' }}>Team Lead</option>
                            <option value="Support Manager" {{ old('position') == 'Support Manager' ? 'selected' : '' }}>Support Manager</option>
                        </select>
                        @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-key mr-2"></i> Password
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-building mr-2"></i> Assignment
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" id="department_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <select name="status" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 px-6 py-3">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    <i class="bi bi-tags mr-2"></i> Categories
                </h2>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Assigned Categories
                    </label>
                    <select name="category_ids[]" id="category_ids" multiple
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            style="min-height: 150px;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-department-id="{{ $category->department_id }}"
                                    {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <i class="bi bi-info-circle"></i> Hold Ctrl (Windows) or Cmd (Mac) to select multiple categories
                    </p>
                    @error('category_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg shadow-sm transition">
                    <i class="bi bi-plus-circle mr-2"></i> Create Agent
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const employeeIdField = document.getElementById('employee_id');
    const categorySelect = document.getElementById('category_ids');
    const form = document.getElementById('createAgentForm');
    
    // Store all category options
    const allCategories = Array.from(categorySelect.options);
    
    // Function to generate employee ID
    function generateEmployeeId() {
        const departmentId = departmentSelect.value;
        
        if (!departmentId) {
            employeeIdField.value = '';
            return;
        }
        
        // Show loading indicator
        employeeIdField.classList.add('opacity-50');
        employeeIdField.placeholder = 'Generating...';
        
        // Fetch generated employee ID from server
        fetch(`/admin/departments/${departmentId}/generate-employee-id`)
            .then(response => response.json())
            .then(data => {
                if (data.employee_id) {
                    employeeIdField.value = data.employee_id;
                } else {
                    employeeIdField.value = '';
                    console.error('No employee ID generated');
                }
            })
            .catch(error => {
                console.error('Error generating employee ID:', error);
                employeeIdField.value = '';
            })
            .finally(() => {
                employeeIdField.classList.remove('opacity-50');
                employeeIdField.placeholder = '';
            });
    }
    
    // Filter categories by department
    function filterCategoriesByDepartment() {
        const selectedDepartmentId = departmentSelect.value;
        
        // Clear current options
        categorySelect.innerHTML = '';
        
        // Filter and add only categories that belong to the selected department
        allCategories.forEach(option => {
            const categoryDepartmentId = option.getAttribute('data-department-id');
            
            if (!selectedDepartmentId || categoryDepartmentId === selectedDepartmentId) {
                const newOption = document.createElement('option');
                newOption.value = option.value;
                newOption.textContent = option.textContent;
                newOption.setAttribute('data-department-id', categoryDepartmentId);
                
                // Preserve selected state from old option
                if (option.selected) {
                    newOption.selected = true;
                }
                
                categorySelect.appendChild(newOption);
            }
        });
        
        // If no categories found for this department, show a disabled option
        if (categorySelect.options.length === 0) {
            const noOption = document.createElement('option');
            noOption.value = '';
            noOption.textContent = 'No categories available for this department';
            noOption.disabled = true;
            categorySelect.appendChild(noOption);
        }
    }
    
    // Generate employee ID when department changes
    departmentSelect.addEventListener('change', function() {
        generateEmployeeId();
        filterCategoriesByDepartment();
    });
    
    // Validate form before submission
    if (form) {
        form.addEventListener('submit', function(e) {
            const employeeId = employeeIdField.value.trim();
            if (!employeeId) {
                e.preventDefault();
                alert('Please select a department first to generate an Employee ID.');
                return false;
            }
        });
    }
    
    // Initial filter on page load
    filterCategoriesByDepartment();
    
    // If department is pre-selected (e.g., from old form submission), generate ID
    if (departmentSelect.value) {
        generateEmployeeId();
    }
});
</script>
@endsection