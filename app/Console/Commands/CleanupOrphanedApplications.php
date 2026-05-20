<?php

namespace App\Console\Commands;

use App\Models\AgentApplication;
use Illuminate\Console\Command;

class CleanupOrphanedApplications extends Command
{
    protected $signature = 'applications:cleanup-orphaned {--delete : Delete orphaned applications}';

    protected $description = 'Find and optionally delete approved applications without corresponding agents';

    public function handle()
    {
        $this->info('🔍 Searching for orphaned approved applications...');
        
        $orphanedApplications = AgentApplication::where('status', 'approved')
            ->doesntHave('user')
            ->get();

        if ($orphanedApplications->isEmpty()) {
            $this->info('✅ No orphaned applications found!');
            return 0;
        }

        $this->warn('⚠️  Found ' . $orphanedApplications->count() . ' orphaned approved application(s):');
        $this->line('');

        $this->table(
            ['ID', 'Name', 'Email', 'Status', 'Created At'],
            $orphanedApplications->map(fn ($app) => [
                $app->id,
                $app->full_name,
                $app->email,
                $app->status,
                $app->created_at->format('Y-m-d H:i:s'),
            ])->toArray()
        );

        if ($this->option('delete')) {
            if (!$this->confirm('Delete these ' . $orphanedApplications->count() . ' orphaned application(s)?', false)) {
                $this->info('Cancelled.');
                return 0;
            }

            $deleted = 0;
            foreach ($orphanedApplications as $application) {
                // Delete associated files
                if ($application->resume_path && \Storage::exists($application->resume_path)) {
                    \Storage::delete($application->resume_path);
                }
                if ($application->cover_letter_path && \Storage::exists($application->cover_letter_path)) {
                    \Storage::delete($application->cover_letter_path);
                }

                $application->delete();
                $deleted++;
            }

            $this->info('✅ Successfully deleted ' . $deleted . ' orphaned application(s).');
            return 0;
        }

        $this->line('');
        $this->info('💡 Run with --delete flag to remove these records:');
        $this->line('   php artisan applications:cleanup-orphaned --delete');
        
        return 0;
    }
}
