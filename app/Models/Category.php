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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Accessor for tickets count
    public function getTicketsCountAttribute()
    {
        return $this->tickets()->count();
    }
}