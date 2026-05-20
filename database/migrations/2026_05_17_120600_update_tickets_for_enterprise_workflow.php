<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('sla_policy_id')->nullable()->after('department_id')->constrained('ticket_sla_policies')->onDelete('set null');
            $table->timestamp('response_due_at')->nullable()->after('sla_policy_id');
            $table->timestamp('resolution_due_at')->nullable()->after('response_due_at');
            $table->timestamp('last_activity_at')->nullable()->after('resolution_due_at');
            $table->timestamp('escalated_at')->nullable()->after('last_activity_at');
            $table->timestamp('reopened_at')->nullable()->after('escalated_at');
            $table->unsignedInteger('reopened_count')->default(0)->after('reopened_at');
            $table->boolean('is_overdue')->default(false)->after('reopened_count');
            $table->boolean('is_sla_breached')->default(false)->after('is_overdue');
            $table->text('cancellation_reason')->nullable()->after('is_sla_breached');
            $table->foreignId('created_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
        });

        DB::statement("ALTER TABLE tickets MODIFY status ENUM('open', 'assigned', 'in_progress', 'pending', 'pending_user_response', 'pending_admin_approval', 'escalated', 'resolved', 'closed', 'reopened', 'canceled') DEFAULT 'open'");
        DB::statement("ALTER TABLE tickets MODIFY priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium'");

        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['sla_policy_id', 'response_due_at', 'resolution_due_at']);
            $table->index('is_overdue');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['sla_policy_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'sla_policy_id',
                'response_due_at',
                'resolution_due_at',
                'last_activity_at',
                'escalated_at',
                'reopened_at',
                'reopened_count',
                'is_overdue',
                'is_sla_breached',
                'cancellation_reason',
                'created_by',
                'updated_by',
            ]);
        });

        DB::statement("ALTER TABLE tickets MODIFY status ENUM('open', 'in_progress', 'pending', 'resolved', 'closed', 'canceled') DEFAULT 'open'");
        DB::statement("ALTER TABLE tickets MODIFY priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium'");
    }
};
