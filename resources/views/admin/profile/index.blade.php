@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your personal information</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700">
                    <!-- Avatar -->
                    <div class="relative inline-block">
                        <img id="avatarPreview" src="{{ $admin->avatar_url ?? 'https://ui-avatars.com/api/?background=7c3aed&color=fff&name=' . urlencode($admin->name) }}"
                             alt="Admin Avatar"
                             class="w-24 h-24 mx-auto rounded-full object-cover shadow-lg border-4 border-white dark:border-gray-800">
                        <button onclick="document.getElementById('avatarInput').click()" 
                                class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-purple-600 hover:bg-purple-700 text-white flex items-center justify-center shadow-lg transition">
                            <i class="bi bi-camera-fill text-xs"></i>
                        </button>
                        <input type="file" id="avatarInput" class="hidden" accept="image/*">
                    </div>
                    @if($admin->avatar)
                    <div class="mt-4 flex justify-center">
                        <button id="removeAvatarButton" type="button"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-all duration-200 font-medium border border-red-200 dark:border-red-800">
                            <i class="bi bi-trash3"></i>
                            <span>Remove Avatar</span>
                        </button>
                    </div>
                    @endif
                    <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $admin->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        @if($admin->role == 'admin') Administrator
                        @elseif($admin->role == 'agent') Support Agent
                        @else End User
                        @endif
                    </p>
                    <div class="mt-2">
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                            {{ ucfirst($admin->role) }}
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    <div class="flex items-center gap-3 p-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-envelope w-5 text-gray-400"></i>
                        <span class="truncate">{{ $admin->email }}</span>
                    </div>
                    @if($admin->phone)
                    <div class="flex items-center gap-3 p-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-telephone w-5 text-gray-400"></i>
                        <span>{{ $admin->phone }}</span>
                    </div>
                    @endif
                    @if($admin->department)
                    <div class="flex items-center gap-3 p-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-building w-5 text-gray-400"></i>
                        <span>{{ $admin->department }}</span>
                    </div>
                    @endif
                    @if($admin->position)
                    <div class="flex items-center gap-3 p-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-briefcase w-5 text-gray-400"></i>
                        <span>{{ $admin->position }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-3 p-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="bi bi-calendar w-5 text-gray-400"></i>
                        <span>Joined {{ $admin->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
                </div>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                            <input type="text" name="department" value="{{ old('department', $admin->department) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- Position -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position / Title</label>
                            <input type="text" name="position" value="{{ old('position', $admin->position) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.profile.password') }}" 
                           class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition inline-flex items-center gap-2">
                            <i class="bi bi-key"></i>
                            <span>Change Password</span>
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition inline-flex items-center gap-2">
                            <i class="bi bi-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Admin Statistics Card -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-bar-chart-stats text-purple-500"></i>
                        System Overview
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_users'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Total Users</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_tickets'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Total Tickets</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $stats['resolved_tickets'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Resolved Tickets</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ $stats['open_tickets'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Open Tickets</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-5 right-5 z-[9999] hidden transform transition-all duration-300 translate-x-full opacity-0">
    <div class="flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg" id="toastMessage">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span class="text-sm font-medium">Message</span>
    </div>
</div>

<script>
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    if (type === 'success') {
        toastMessage.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg bg-green-600 text-white';
        toastMessage.innerHTML = '<i class="bi bi-check-circle-fill text-lg"></i><span class="text-sm font-medium">' + message + '</span>';
    } else if (type === 'error') {
        toastMessage.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg bg-red-600 text-white';
        toastMessage.innerHTML = '<i class="bi bi-x-circle-fill text-lg"></i><span class="text-sm font-medium">' + message + '</span>';
    } else {
        toastMessage.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg bg-blue-600 text-white';
        toastMessage.innerHTML = '<i class="bi bi-info-circle-fill text-lg"></i><span class="text-sm font-medium">' + message + '</span>';
    }
    
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-0', 'opacity-100');
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);
    }, 3000);
}

document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('avatar', file);
    
    showToast('Uploading avatar...', 'info');
    
    fetch('{{ route("admin.profile.avatar") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showToast(data.message || 'Failed to upload avatar', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to upload avatar', 'error');
    });
});

const removeAvatarBtn = document.getElementById('removeAvatarButton');
if (removeAvatarBtn) {
    removeAvatarBtn.addEventListener('click', function() {
        showToast('Removing avatar...', 'info');
        
        fetch('{{ route("admin.profile.avatar.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avatarPreview').src = data.avatar_url + '?' + Date.now();
                removeAvatarBtn.remove();
                showToast('Avatar removed successfully!', 'success');
            } else {
                showToast(data.message || 'Failed to remove avatar', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to remove avatar', 'error');
        });
    });
}
</script>
@endsection