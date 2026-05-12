<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['ticket_id', 'message', 'responder_type', 'responder_name'];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}