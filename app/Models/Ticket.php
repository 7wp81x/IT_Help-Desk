<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'category_id',
        'department_id',
        'subject',
        'description',
        'status',
        'priority',
        'attachment',
        'resolved_at',
        'closed_at',
        'created_by'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function responses()
    {
        return $this->comments();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Add this relationship for attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id');
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
            'open' => '#0d6efd',
            'in_progress' => '#6f42c1',
            'pending' => '#fd7e14',
            'resolved' => '#198754',
            'closed' => '#6c757d',
            default => '#6c757d',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'low' => '#0dcaf0',
            'medium' => '#20c997',
            'high' => '#ffc107',
            'urgent' => '#dc3545',
            default => '#6c757d',
        };
    }

    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'open' => 'fa-folder-open',
            'in_progress' => 'fa-spinner',
            'pending' => 'fa-clock',
            'resolved' => 'fa-check-circle',
            'closed' => 'fa-times-circle',
            default => 'fa-info-circle',
        };
    }

    public function getPriorityIconAttribute()
    {
        return match ($this->priority) {
            'low' => 'fa-arrow-down',
            'medium' => 'fa-minus',
            'high' => 'fa-arrow-up',
            'urgent' => 'fa-exclamation-triangle',
            default => 'fa-bell',
        };
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