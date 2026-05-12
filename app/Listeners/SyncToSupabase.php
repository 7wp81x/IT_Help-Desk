<?php

namespace App\Listeners;

use App\Events\ModelSynced;
use Illuminate\Support\Facades\Http;

class SyncToSupabase
{
    public function handle(ModelSynced $event)
    {
        $model = $event->model;
        $action = $event->action;
        
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SERVICE_KEY');
        
        $table = $model->getTable();
        
        try {
            switch ($action) {
                case 'created':
                case 'updated':
                    Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Content-Type' => 'application/json',
                        'Prefer' => 'resolution=merge-duplicates'
                    ])->post($supabaseUrl . '/rest/v1/' . $table, $model->toArray());
                    break;
                    
                case 'deleted':
                    Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                    ])->delete($supabaseUrl . '/rest/v1/' . $table . '?id=eq.' . $model->id);
                    break;
            }
        } catch (\Exception $e) {
            \Log::error('Supabase sync failed: ' . $e->getMessage());
        }
    }
}