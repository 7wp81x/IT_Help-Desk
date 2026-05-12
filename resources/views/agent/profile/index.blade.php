@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your account settings</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center">
                    <div class="relative inline-block mb-4">
                        <img id="avatarPreview" src="{{ $agent->avatar_url ?? 'https://ui-avatars.com/api/?background=22c55e&color=fff&name=' . urlencode($agent->name) }}"
                             alt="Agent Avatar"
                             class="w-32 h-32 rounded-full object-cover ring-4 ring-green-100 dark:ring-green-900/30 shadow-lg">
                        <button onclick="document.getElementById('avatarInput').click()"
                                class="absolute bottom-0 right-0 w-10 h-10 rounded-full bg-green-600 hover:bg-green-700 text-white flex items-center justify-center shadow-lg transition">
                            <i class="bi bi-camera-fill text-sm"></i>
                        </button>
                        <input type="file" id="avatarInput" class="hidden" accept="image/*">
                    </div>
                    @if($agent->avatar)
                    <div class="mt-4 flex justify-center">
                        <button id="removeAvatarButton" type="button"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                            <i class="bi bi-trash"></i>
                            Remove Avatar
                        </button>
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $agent->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Support Agent</p>

                    <div class="mt-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            <i class="bi bi-headset text-xs"></i>
                            Agent
                        </span>
                    </div>
                </div>
                <div class="p-4 space-y-3 text-left">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1 break-all">{{ $agent->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone Number</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $agent->phone ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agent ID</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1 font-mono">{{ $agent->employee_id ?? 'Not assigned' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Department</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $agent->department ?? 'Not specified' }}</p>
                    </div>
                    @if($agent->approved_at)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Approval Date</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $agent->approved_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member Since</p>
                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $agent->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Assigned Tickets</p>
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

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-person-circle text-green-500"></i>
                        Update Profile Information
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('agent.profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                <input type="text" name="name" required
                                       value="{{ old('name', $agent->name) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                <input type="email" name="email" required
                                       value="{{ old('email', $agent->email) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                <input type="text" name="phone"
                                       value="{{ old('phone', $agent->phone) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                                <input type="text" name="department"
                                       value="{{ old('department', $agent->department) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('agent.profile.password') }}"
                               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition inline-flex items-center gap-2">
                                <i class="bi bi-key"></i>
                                <span>Change Password</span>
                            </a>
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition inline-flex items-center gap-2">
                                <i class="bi bi-save"></i>
                                <span>Update Profile</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('avatar', file);

    fetch('{{ route("agent.profile.avatar") }}', {
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
            alert('Avatar updated successfully!');
        }
    })
    .catch(error => console.error('Error:', error));
});

const removeAvatarBtn = document.getElementById('removeAvatarButton');
if (removeAvatarBtn) {
    removeAvatarBtn.addEventListener('click', function() {
        if (!confirm('Remove your uploaded avatar and restore the default image?')) {
            return;
        }

        fetch('{{ route("agent.profile.avatar.remove") }}', {
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
            }
        })
        .catch(error => console.error('Error:', error));
    });
}
</script>
@endsection
