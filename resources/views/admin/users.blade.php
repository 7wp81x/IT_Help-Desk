@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Manage Users</h2>
            <p class="text-muted">View and manage all system users</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section mb-4" data-aos="fade-up">
        <div class="row">
            <div class="col-md-4">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="userSearch" class="form-control" placeholder="Search users...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="roleFilter" class="form-select">
                    <option value="all">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="agent">Agent</option>
                    <option value="user">User</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card" data-aos="fade-up">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Tickets</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach($users as $user)
                        <tr class="searchable-row" data-role="{{ $user->roles->first()->name ?? 'user' }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->avatar_url }}" width="35" height="35" class="rounded-circle me-2">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->department ?? '—' }}</td>
                            <td>
                                <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                        <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                        <option value="agent" {{ $user->hasRole('agent') ? 'selected' : '' }}>Agent</option>
                                        <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>User</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $user->tickets_count ?? 0 }}</td>
                            <td>
                                <span class="badge-status" style="background: {{ $user->is_active ? '#10B98120' : '#EF444420' }}; color: {{ $user->is_active ? '#10B981' : '#EF4444' }};">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }}" 
                                        onclick="toggleUserStatus({{ $user->id }})">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                                <form id="toggle-form-{{ $user->id }}" action="{{ route('admin.users.toggle', $user) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleUserStatus(userId) {
        Swal.fire({
            title: 'Confirm Action',
            text: 'Are you sure you want to change this user\'s status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`toggle-form-${userId}`).submit();
            }
        });
    }
    
    // Filter users
    $('#userSearch, #roleFilter').on('keyup change', function() {
        const searchTerm = $('#userSearch').val().toLowerCase();
        const roleFilter = $('#roleFilter').val();
        
        $('#usersTableBody tr').each(function() {
            let show = true;
            const text = $(this).text().toLowerCase();
            const role = $(this).data('role');
            
            if (searchTerm && !text.includes(searchTerm)) show = false;
            if (roleFilter !== 'all' && role !== roleFilter) show = false;
            
            $(this).toggle(show);
        });
    });
</script>
@endpush
@endsection