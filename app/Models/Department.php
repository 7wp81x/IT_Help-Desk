<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'is_active'
    ];

    // Relationship using the 'department' column in users table
    public function users()
    {
        return $this->hasMany(User::class, 'department', 'name');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}