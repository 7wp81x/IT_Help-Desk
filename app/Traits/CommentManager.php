<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\MessageNotification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Str;

trait CommentManager
{
    /**
     * Generate a unique message anchor for a comment
     */
    public function generateMessageAnchor(Comment $comment): string
    {
        $anchor = 'message-' . $comment->id;
        return $anchor;
    }

    /**
     * Delete a comment with soft delete
     */
    public function performCommentDeletion(Comment $comment, User $deletedBy): bool
    {
        // Check authorization
        if (!$comment->canBeDeletedBy($deletedBy)) {
            return false;
        }

        // Update deletion info
        $comment->update([
            'deleted_by' => $deletedBy->id,
        ]);

        // Soft delete the comment
        $comment->delete();

        // Log the deletion
        if ($comment->ticket) {
            $comment->ticket->logs()->create([
                'user_id' => $deletedBy->id,
                'action' => 'comment_deleted',
                'metadata' => [
                    'comment_id' => $comment->id,
                    'deleted_by_name' => $deletedBy->name,
                    'deleted_by_role' => $deletedBy->role,
                ],
            ]);
        }

        return true;
    }

    /**
     * Create a message notification
     */
    public function createMessageNotification(
        User $notifyUser,
        Ticket $ticket,
        Comment $comment,
        User $triggeredBy,
        string $type,
        string $message
    ): ?MessageNotification {
        // Don't notify if it's the same user
        if ($notifyUser->id === $triggeredBy->id) {
            return null;
        }

        return MessageNotification::create([
            'user_id' => $notifyUser->id,
            'ticket_id' => $ticket->id,
            'comment_id' => $comment->id,
            'triggered_by' => $triggeredBy->id,
            'type' => $type,
            'message' => $message,
        ]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadNotificationCount(User $user): int
    {
        return MessageNotification::forUser($user->id)
            ->unread()
            ->count();
    }

    /**
     * Mark a comment notification as read
     */
    public function markCommentNotificationAsRead(Comment $comment, User $user): void
    {
        MessageNotification::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get newest comments for real-time refresh
     */
    public function getNewComments(Ticket $ticket, $lastCheckedAt = null)
    {
        $query = $ticket->comments()
            ->notDeleted()
            ->with(['user', 'attachments'])
            ->orderBy('created_at', 'asc');

        if ($lastCheckedAt) {
            $query->where('created_at', '>', $lastCheckedAt);
        }

        return $query->get();
    }
}
