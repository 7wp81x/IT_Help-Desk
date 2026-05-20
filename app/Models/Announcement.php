<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
        'published_at',
        'audience',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function reads()
    {
        return $this->hasMany(UserAnnouncementRead::class);
    }

    // Scope for active announcements
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('published_at', '<=', now());
    }

    public function scopeForAudience($query, ?string $role)
    {
        return $query->where(function ($query) use ($role) {
            $query->where('audience', 'all');

            if ($role) {
                $query->orWhere('audience', $role);
            }
        });
    }

    // Check if user has read this announcement
    public function isReadBy(User $user)
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    public function isVisibleTo(User $user)
    {
        return $this->audience === 'all' || $this->audience === $user->role;
    }
}
