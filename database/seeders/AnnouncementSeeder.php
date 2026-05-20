<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Announcement::firstOrCreate([
            'title' => 'Welcome to the IT Helpdesk',
        ], [
            'content' => 'This is a seeded announcement visible to all users.',
            'is_active' => true,
            'published_at' => now()->subDay(),
            'audience' => 'all',
        ]);

        Announcement::firstOrCreate([
            'title' => 'Admin Panel Update',
        ], [
            'content' => 'Admins: new bulk actions and reporting added to the admin panel.',
            'is_active' => true,
            'published_at' => now()->subHours(2),
            'audience' => 'admin',
        ]);
    }
}
