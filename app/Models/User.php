<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, MustVerifyEmailTrait;

    protected $fillable = [
        'name', 
        'email', 
        'password',
        'phone',           // ← Added
        'avatar',
        'role',            // ← Added (user/agent)
        'status',          // ← Added (active/inactive)
        'department',      // ← Added
        'position',        // ← Added
        'employee_id',     // ← Added
        'specialization',  // ← Added
        'tickets_handled', // ← Added
        'rating',          // ← Added
        'skills',          // ← Added
        'last_login_at',   // ← Added
        'last_login_ip',   // ← Added
        'agent_application_id',
        'approved_at',     // ← Added
        'approved_by',     // ← Added
        'is_active',       // Keep this if you use it
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'approved_at' => 'datetime',
        'rating' => 'float',
        'tickets_handled' => 'integer',
        'preferences' => 'array',
        'notification_settings' => 'array',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
    }

    public function agentRatings()
    {
        return $this->hasMany(AgentRating::class, 'agent_id');
    }

    public function givenRatings()
    {
        return $this->hasMany(AgentRating::class, 'user_id');
    }

    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    public function announcementReads()
    {
        return $this->hasMany(UserAnnouncementRead::class);
    }

    public function agentApplication()
    {
        return $this->belongsTo(AgentApplication::class, 'agent_application_id');
    }

    // Role checks (using simple role column, not Spatie)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }
    
    public function isUser()
    {
        return $this->role === 'user';
    }

    // Stats
    public function getOpenTicketsCount()
    {
        return $this->tickets()->whereIn('status', ['open', 'in_progress', 'pending'])->count();
    }

    public function getResolvedTicketsCount()
    {
        return $this->tickets()->where('status', 'resolved')->count();
    }

    // Accessor for avatar
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?background=2563EB&color=fff&name=' . urlencode($this->name);
    }
    
    // Helper to increment tickets handled
    public function incrementTicketsHandled()
    {
        $this->increment('tickets_handled');
    }
    
    // Helper to update rating
    public function updateRating($newRating)
    {
        // Calculate average rating
        $total = ($this->rating * $this->tickets_handled) + $newRating;
        $this->rating = $total / ($this->tickets_handled + 1);
        $this->save();
    }
}