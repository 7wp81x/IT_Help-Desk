<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        DB::statement("ALTER TABLE tickets MODIFY COLUMN category_id BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open','assigned','in_progress','pending','pending_user_response','pending_admin_approval','escalated','resolved','closed','reopened','canceled') NOT NULL DEFAULT 'open'");

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        DB::statement("ALTER TABLE tickets MODIFY COLUMN category_id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open','in_progress','pending','resolved','closed','canceled') NOT NULL DEFAULT 'open'");

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
};
