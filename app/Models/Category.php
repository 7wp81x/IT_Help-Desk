<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'priority_level',
        'is_active',
        'department_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Accessor for tickets count
    public function getTicketsCountAttribute()
    {
        return $this->tickets()->count();
    }
    

    // Accessor for knowledge bases count
    public function getKnowledgeBasesCountAttribute()
    {
        return 0;
    }
}