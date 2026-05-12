<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseSync
{
    protected $url;
    protected $key;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_SERVICE_KEY');
    }

    /**
     * Save user to Supabase
     */
    public function saveUser($user)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/rest/v1/users', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role ?? 'user',
                'status' => $user->status ?? 'active',
                'department' => $user->department ?? null,
                'position' => $user->position ?? null,
                'employee_id' => $user->employee_id ?? null,
                'specialization' => $user->specialization ?? null,
                'skills' => $user->skills ?? null,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ]);

            if ($response->successful()) {
                Log::info('User saved to Supabase: ' . $user->email);
                return true;
            } else {
                Log::error('Supabase error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Supabase exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save ticket to Supabase
     */
    public function saveTicket($ticket)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->key,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/rest/v1/tickets', [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => $ticket->title,
                'description' => $ticket->description,
                'category_id' => $ticket->category_id,
                'priority' => $ticket->priority,
                'status' => $ticket->status,
                'user_id' => $ticket->user_id,
                'assigned_to' => $ticket->assigned_to,
                'created_at' => $ticket->created_at,
                'updated_at' => $ticket->updated_at,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Supabase ticket error: ' . $e->getMessage());
            return false;
        }
    }
}