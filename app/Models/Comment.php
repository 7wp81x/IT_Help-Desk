<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'content',
        'is_internal',
        'reply_type',
        'mentioned_user_ids',
        'attachment',
        'message_anchor',
        'deleted_by',
        'deletion_reason',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'mentioned_user_ids' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'comment_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function notifications()
    {
        return $this->hasMany(MessageNotification::class, 'comment_id');
    }

    /**
     * Check if user can delete this comment
     * Everyone can only delete their own comments
     */
    public function canBeDeletedBy(User $user): bool
    {
        // Everyone can delete only their own comments
        return $this->user_id === $user->id;
    }

    /**
     * Scope to exclude deleted messages
     */
    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope to include deleted messages
     */
    public function scopeIncludingDeleted($query)
    {
        return $query->withTrashed();
    }
}