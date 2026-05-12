@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your account settings</p>
        </div>
    </div>

    @if (!$user->email_verified_at)
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4 text-sm text-yellow-900 dark:text-yellow-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="font-semibold">Email not verified</p>
                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-200">Please verify your email to unlock full support features. If you didn&rsquo;t receive it, resend the verification email.</p>
                </div>
                <form method="POST" action="{{ route('verification.resend') }}" class="sm:inline-flex">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-yellow-600 text-white px-4 py-2 text-sm font-medium hover:bg-yellow-700 transition">
                        Resend verification email
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center">
                    <!-- Avatar -->
                    <div class="relative inline-block mb-4">
                        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?background=6366f1&color=fff&name=' . urlencode($user->name) }}" 
                             width="120" height="120" 
                             class="w-32 h-32 rounded-full object-cover ring-4 ring-blue-100 dark:ring-blue-900/30"
                             id="avatarPreview">
                        <button onclick="document.getElementById('avatarInput').click()" 
                                class="absolute bottom-0 right-0 w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow-lg transition">
                            <i class="bi bi-camera-fill text-sm"></i>
                        </button>
                        <input type="file" id="avatarInput" class="hidden" accept="image/*">
                    </div>
                    @if($user->avatar)
                    <div class="mt-4 flex justify-center">
                        <button id="removeAvatarButton" type="button"
        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-all duration-200 font-medium border border-red-200 dark:border-red-800">
    <i class="bi bi-trash3"></i>
    <span>Remove Avatar</span>
</button>
                    </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                    
                    <div class="mt-3 space-y-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full 
                            @if($user->role == 'admin') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                            @elseif($user->role == 'agent') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                            @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @endif">
                            <i class="bi 
                                @if($user->role == 'admin') bi-shield-lock
                                @elseif($user->role == 'agent') bi-headset
                                @else bi-person
                                @endif text-xs"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                            <i class="bi {{ $user->status === 'active' ? 'bi-check-circle' : 'bi-x-circle' }} text-xs"></i>
                            {{ ucfirst($user->status ?? 'inactive') }} Account
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full {{ $user->email_verified_at ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                            <i class="bi {{ $user->email_verified_at ? 'bi-envelope-check' : 'bi-envelope-exclamation' }} text-xs"></i>
                            {{ $user->email_verified_at ? 'Email Verified' : 'Email Not Verified' }}
                        </span>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
                    
                    <div class="space-y-3 text-left">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone Number</p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                <i class="bi bi-telephone mr-2"></i>
                                {{ $user->phone ?? 'Not specified' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member Since</p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                <i class="bi bi-calendar mr-2"></i>
                                {{ $user->created_at->format('F d, Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                <i class="bi bi-clock mr-2"></i>
                                {{ $user->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Open Tickets</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['open_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-green-600 dark:text-green-400">Resolved</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['resolved_tickets'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-blue-600 dark:text-blue-400">Comments</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['comments_count'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Update Profile Form with Change Password button inside -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-500"></i>
                        Update Profile Information
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('user.profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                <input type="text" name="name" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                <input type="email" name="email" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                <input type="text" name="phone" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                       value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition inline-flex items-center gap-2">
                                <i class="bi bi-save"></i>
                                <span>Update Profile</span>
                            </button>
                            <a href="{{ route('user.profile.password') }}" 
                               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition inline-flex items-center gap-2">
                                <i class="bi bi-key"></i>
                                <span>Change Password</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-2">Delete Account</h3>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-4">
                This action is irreversible. All your data will be permanently deleted.
            </p>
            <form action="{{ route('user.profile.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enter your password to confirm</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('deleteAccountModal').classList.add('hidden')" 
                            class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-5 right-5 z-[9999] hidden transform transition-all duration-300 translate-x-full opacity-0">
    <div class="flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white" id="toastMessage">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span class="text-sm font-medium">Message</span>
    </div>
</div>

<script>
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    // Set style based on type
    if (type === 'success') {
        toastMessage.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg bg-green-600';
        toastMessage.innerHTML = '<i class="bi bi-check-circle-fill text-lg"></i><span class="text-sm font-medium">' + message + '</span>';
    } else if (type === 'error') {
        toastMessage.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg bg-red-600';
        toastMessage.innerHTML = '<i class="bi bi-x-circle-fill text-lg"></i><span class="text-sm font-medium">' + message + '</span>';
    } else {
        toastMessage.className = 'flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg bg-blue-600';
        toastMessage.innerHTML = '<i class="bi bi-info-circle-fill text-lg"></i><span class="text-sm font-medium">' + message + '</span>';
    }
    
    // Show toast
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    // Auto hide after 3 seconds
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
    
    fetch('{{ route("user.profile.avatar") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('avatarPreview').src = data.avatar_url + '?' + Date.now();
            showToast('Avatar updated successfully!', 'success');
            
            // Add remove button if not exists
            const removeBtnContainer = document.querySelector('.relative.inline-block + .mt-4');
            if (!document.getElementById('removeAvatarButton') && data.has_avatar) {
                location.reload();
            }
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
        fetch('{{ route("user.profile.avatar.remove") }}', {
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