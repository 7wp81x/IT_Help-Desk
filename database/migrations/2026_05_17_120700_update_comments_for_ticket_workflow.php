<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('reply_type', ['public', 'internal', 'note'])->default('public')->after('content');
            $table->json('mentioned_user_ids')->nullable()->after('reply_type');
            $table->index(['ticket_id', 'reply_type']);
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['reply_type', 'mentioned_user_ids']);
        });
    }
};
