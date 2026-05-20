<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use App\Models\TicketSlaPolicy;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\TicketAssignment;
use App\Models\TicketLog;
use App\Models\TicketWatcher;
use App\Models\AgentRating;

class Ticket extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PENDING_USER_RESPONSE = 'pending_user_response';
    public const STATUS_PENDING_ADMIN_APPROVAL = 'pending_admin_approval';
    public const STATUS_ESCALATED = 'escalated';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_REOPENED = 'reopened';
    public const STATUS_CANCELED = 'canceled';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'category_id',
        'department_id',
        'sla_policy_id',
        'subject',
        'description',
        'status',
        'priority',
        'response_due_at',
        'resolution_due_at',
        'last_activity_at',
        'escalated_at',
        'reopened_at',
        'reopened_count',
        'is_overdue',
        'is_sla_breached',
        'cancellation_reason',
        'created_by',
        'updated_by',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'escalated_at' => 'datetime',
        'reopened_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'is_overdue' => 'boolean',
        'is_sla_breached' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo()
    {
        return $this->assignedAgent();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function slaPolicy()
    {
        return $this->belongsTo(TicketSlaPolicy::class, 'sla_policy_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function responses()
    {
        return $this->comments();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id');
    }

    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function watchers()
    {
        return $this->hasMany(TicketWatcher::class);
    }

    public function agentRating()
    {
        return $this->hasOne(AgentRating::class);
    }

    public function getSubjectAttribute()
    {
        return $this->title;
    }

    public function setSubjectAttribute($value)
    {
        $this->attributes['title'] = $value;
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_OPEN => '#0d6efd',
            self::STATUS_ASSIGNED => '#0d6efd',
            self::STATUS_IN_PROGRESS => '#6f42c1',
            self::STATUS_PENDING => '#fd7e14',
            self::STATUS_PENDING_USER_RESPONSE => '#fd7e14',
            self::STATUS_PENDING_ADMIN_APPROVAL => '#fd7e14',
            self::STATUS_ESCALATED => '#dc3545',
            self::STATUS_RESOLVED => '#198754',
            self::STATUS_CLOSED => '#6c757d',
            self::STATUS_REOPENED => '#0d6efd',
            self::STATUS_CANCELED => '#6c757d',
            default => '#6c757d',
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => '#0dcaf0',
            self::PRIORITY_MEDIUM => '#20c997',
            self::PRIORITY_HIGH => '#ffc107',
            self::PRIORITY_URGENT => '#dc3545',
            default => '#6c757d',
        };
    }

    public function getPriorityLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->priority));
    }

    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'fa-folder-open',
            self::STATUS_ASSIGNED => 'fa-user-tag',
            self::STATUS_IN_PROGRESS => 'fa-spinner',
            self::STATUS_PENDING => 'fa-clock',
            self::STATUS_PENDING_USER_RESPONSE => 'fa-user-clock',
            self::STATUS_PENDING_ADMIN_APPROVAL => 'fa-user-shield',
            self::STATUS_ESCALATED => 'fa-exclamation-triangle',
            self::STATUS_RESOLVED => 'fa-check-circle',
            self::STATUS_CLOSED => 'fa-times-circle',
            self::STATUS_REOPENED => 'fa-redo',
            self::STATUS_CANCELED => 'fa-ban',
            default => 'fa-info-circle',
        };
    }

    public function getPriorityIconAttribute()
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'fa-arrow-down',
            self::PRIORITY_MEDIUM => 'fa-minus',
            self::PRIORITY_HIGH => 'fa-arrow-up',
            self::PRIORITY_URGENT => 'fa-exclamation-triangle',
            default => 'fa-bell',
        };
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_overdue', true)->orWhere('resolution_due_at', '<', now());
    }

    public function scopeAssignedToAgent($query, int $agentId)
    {
        return $query->where('assigned_to', $agentId);
    }

    public function scopeDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function isAgentAssigned(User $agent): bool
    {
        return $this->assigned_to === $agent->id;
    }

    public function isEscalated(): bool
    {
        return $this->status === self::STATUS_ESCALATED;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    // Auto-delete attachments when ticket is deleted
    protected static function booted()
    {
        static::deleting(function ($ticket) {
            foreach ($ticket->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment->path)) {
                    Storage::disk('public')->delete($attachment->path);
                }
                $attachment->delete();
            }
        });
    }
}