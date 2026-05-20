<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('notification_id');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->enum('sent_via', ['email', 'database', 'broadcast', 'push', 'toast', 'sms']);
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->timestamp('delivered_at')->nullable();
            $table->text('error_message')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['recipient_id', 'status']);
            $table->index('notification_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
