<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachment', function (Blueprint $table) {
            $table->foreignId('comment_id')->nullable()->after('ticket_id')->constrained('comments')->onDelete('cascade');
            $table->boolean('is_internal')->default(false)->after('user_id');
            $table->index(['ticket_id', 'comment_id', 'is_internal']);
        });
    }

    public function down(): void
    {
        Schema::table('attachment', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn(['comment_id', 'is_internal']);
        });
    }
};
