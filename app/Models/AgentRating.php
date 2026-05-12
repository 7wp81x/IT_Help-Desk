<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentRating extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'agent_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
