<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_sla_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->integer('response_time_minutes')->default(60);
            $table->integer('resolution_time_minutes')->default(240);
            $table->integer('warning_threshold_minutes')->default(30);
            $table->integer('escalation_threshold_minutes')->default(120);
            $table->boolean('auto_escalate')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['department_id', 'category_id', 'priority']);
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_sla_policies');
    }
};
