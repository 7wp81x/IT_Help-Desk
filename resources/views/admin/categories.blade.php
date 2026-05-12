@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Manage Categories</h2>
            <p class="text-muted">Organize tickets by categories</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus-circle me-2"></i>Add Category
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="row">
        @foreach($categories as $category)
        <div class="col-md-4 mb-4" data-aos="fade-up">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div style="width: 50px; height: 50px; background: {{ $category->color }}20; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas {{ $category->icon }} fa-2x" style="color: {{ $category->color }};"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">{{ $category->name }}</h5>
                                <small class="text-muted">{{ $category->tickets_count ?? 0 }} tickets</small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="dropdown-item" onclick="editCategory({{ $category->id }})">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger" onclick="deleteCategory({{ $category->id }})">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">{{ $category->description ?? 'No description' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge-status" style="background: {{ $category->color }}20; color: {{ $category->color }};">
                            <i class="fas {{ $category->icon }} me-1"></i>
                            {{ $category->slug }}
                        </span>
                        <span class="badge-status" style="background: {{ $category->is_active ? '#10B98120' : '#EF444420' }}; color: {{ $category->is_active ? '#10B981' : '#EF4444' }};">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon (Font Awesome)</label>
                            <input type="text" name="icon" class="form-control" value="fa-ticket" placeholder="fa-ticket">
                            <small class="text-muted">Example: fa-desktop, fa-code, fa-network-wired</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" class="form-control" value="#3B82F6">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon (Font Awesome)</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" id="edit_color" class="form-control">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="edit_is_active" class="form-check-input" value="1">
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editCategory(id) {
        // Fetch category data via AJAX and populate form
        $.get(`/admin/categories/${id}/edit`, function(category) {
            $('#edit_name').val(category.name);
            $('#edit_description').val(category.description);
            $('#edit_icon').val(category.icon);
            $('#edit_color').val(category.color);
            $('#edit_is_active').prop('checked', category.is_active);
            $('#editCategoryForm').attr('action', `/admin/categories/${id}`);
            $('#editCategoryModal').modal('show');
        });
    }
    
    function deleteCategory(id) {
        Swal.fire({
            title: 'Delete Category',
            text: 'Are you sure? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${id}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection