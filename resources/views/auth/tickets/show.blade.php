@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Ticket #{{ $ticket->ticket_number }}</h2>
            <p class="text-muted">Created {{ $ticket->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <div>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateTicketModal">
                <i class="fas fa-edit me-2"></i>Update Ticket
            </button>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Ticket Details -->
            <div class="card mb-4" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i>Ticket Details
                    </h5>
                    <h4>{{ $ticket->title }}</h4>
                    <div class="mt-3">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                    
                    <!-- Attachments -->
                    @if($ticket->attachments->count() > 0)
                    <div class="mt-4">
                        <h6><i class="fas fa-paperclip me-2"></i>Attachments</h6>
                        <div class="row">
                            @foreach($ticket->attachments as $attachment)
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('tickets.download', $attachment) }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="fas fa-file-alt fa-2x me-2 text-muted"></i>
                                        <div>
                                            <small class="d-block">{{ Str::limit($attachment->original_name, 30) }}</small>
                                            <small class="text-muted">{{ number_format($attachment->size / 1024, 2) }} KB</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mb-4" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-comments me-2"></i>Comments
                        <span class="badge bg-primary ms-2">{{ $ticket->comments->count() }}</span>
                    </h5>
                    
                    <div class="comments-section mb-4" style="max-height: 400px; overflow-y: auto;">
                        @foreach($ticket->comments as $comment)
                        <div class="d-flex mb-3 p-3 border rounded">
                            <img src="{{ $comment->user->avatar_url }}" width="40" height="40" class="rounded-circle me-3">
                            <div class="grow">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        @if($comment->is_internal)
                                            <span class="badge bg-warning ms-2">
                                                <i class="fas fa-lock me-1"></i>Internal
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0">{{ $comment->content }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Add Comment -->
                    <form action="{{ route('tickets.comment', $ticket) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3" placeholder="Write your comment here..." required></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_internal" id="internalComment" value="1">
                            <label class="form-check-label" for="internalComment">
                                <i class="fas fa-lock me-1"></i>Internal Comment (Staff only)
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Post Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card mb-4" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-chart-line me-2"></i>Ticket Status
                    </h5>
                    <div class="mb-3">
                        <label class="text-muted small">Current Status</label>
                        <div>
                            <span class="badge-status" style="background: {{ $ticket->status_color }}20; color: {{ $ticket->status_color }}; padding: 8px 16px;">
                                <i class="fas {{ $ticket->status_icon }} me-2"></i>
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Priority</label>
                        <div>
                            <span class="badge-status" style="background: {{ $ticket->priority_color }}20; color: {{ $ticket->priority_color }}; padding: 8px 16px;">
                                <i class="fas {{ $ticket->priority_icon }} me-2"></i>
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Created by</small>
                        <div class="d-flex align-items-center mt-1">
                            <img src="{{ $ticket->user->avatar_url }}" width="30" height="30" class="rounded-circle me-2">
                            <strong>{{ $ticket->user->name }}</strong>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Assigned to</small>
                        <div class="mt-1">
                            @if($ticket->assignedTo)
                                <img src="{{ $ticket->assignedTo->avatar_url }}" width="30" height="30" class="rounded-circle me-2">
                                {{ $ticket->assignedTo->name }}
                            @else
                                <span class="text-muted">Not assigned yet</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Category</small>
                        <div class="mt-1">
                            <span class="badge-status" style="background: {{ $ticket->category->color }}20; color: {{ $ticket->category->color }};">
                                <i class="fas {{ $ticket->category->icon }} me-1"></i>
                                {{ $ticket->category->name }}
                            </span>
                        </div>
                    </div>
                    @if($ticket->resolved_at)
                    <div class="mb-2">
                        <small class="text-muted">Resolved at</small>
                        <div class="mt-1">
                            {{ $ticket->resolved_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card" data-aos="fade-up">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-history me-2"></i>Activity Log
                    </h5>
                    <div style="max-height: 300px; overflow-y: auto;">
                        @foreach($ticket->activities as $activity)
                        <div class="mb-2 pb-2 border-bottom">
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            <p class="mb-0 small">
                                <strong>{{ $activity->user->name }}</strong>
                                {{ $activity->action }}
                                @if($activity->new_value)
                                    to {{ $activity->new_value }}
                                @endif
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Ticket Modal -->
@if(auth()->user()->isAdmin() || auth()->user()->isAgent())
<div class="modal fade" id="updateTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Ticket #{{ $ticket->ticket_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Auto-refresh comments every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection