<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'recipient_id',
        'sent_via',
        'status',
        'delivered_at',
        'error_message',
        'payload',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'payload' => 'array',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
