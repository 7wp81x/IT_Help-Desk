<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
        'published_at',
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

    // Check if user has read this announcement
    public function isReadBy(User $user)
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }
}
