<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'attachment'; // Use singular table name

    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment_id',
        'filename',
        'original_name',
        'mime_type',
        'size',
        'path'
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}