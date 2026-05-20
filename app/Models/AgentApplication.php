<?php

namespace App\Models;

use App\Models\User;
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
        'cover_letter_path',
        'certifications',
        'resume_path',
        'status',
        'admin_notes',
        'reviewed_by',
        'department_id',
        'specialization',
        'generated_employee_id',
        'generated_password',
    ];

    protected $casts = [
        'certifications' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'agent_application_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getUserAttribute()
    {
        if ($this->relationLoaded('user')) {
            return $this->getRelation('user');
        }

        $user = $this->hasOne(User::class, 'agent_application_id')->first();

        if ($user) {
            return $user;
        }

        return User::where('email', $this->email)
            ->where('role', 'agent')
            ->first();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getCertificationsListAttribute(): string
    {
        return is_array($this->certifications) ? implode(', ', $this->certifications) : ''; 
    }

    public function setPhoneAttribute(?string $value): void
    {
        $this->attributes['phone'] = $this->normalizePhilippinesPhone($value);
    }

    protected function normalizePhilippinesPhone(?string $value): ?string
    {
        $value = trim($value ?? '');

        if ($value === '') {
            return null;
        }

        $value = preg_replace('/[^0-9+]/', '', $value);

        if (preg_match('/^09(\d{9})$/', $value, $matches)) {
            return '+63' . $matches[1];
        }

        if (preg_match('/^639(\d{9})$/', $value)) {
            return '+' . $value;
        }

        if (preg_match('/^\+639(\d{9})$/', $value)) {
            return $value;
        }

        return $value;
    }
}
