<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnnouncementRead extends Model
{
    protected $table = 'user_announcement_reads';

    protected $fillable = [
        'user_id',
        'announcement_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
