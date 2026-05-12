<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'cover_letter',
        'certifications',
        'resume_path',
        'status',
        'admin_notes',
        'reviewed_by',
    ];

    protected $casts = [
        'certifications' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'agent_application_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getCertificationsListAttribute(): string
    {
        return is_array($this->certifications) ? implode(', ', $this->certifications) : ''; 
    }
}
