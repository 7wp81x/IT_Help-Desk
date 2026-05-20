<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSlaPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
        'category_id',
        'priority',
        'response_time_minutes',
        'resolution_time_minutes',
        'warning_threshold_minutes',
        'escalation_threshold_minutes',
        'auto_escalate',
        'active',
    ];

    protected $casts = [
        'auto_escalate' => 'boolean',
        'active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
