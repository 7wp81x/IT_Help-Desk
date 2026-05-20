<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agent_applications', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('status')->constrained('departments')->nullOnDelete();
            $table->text('specialization')->nullable()->after('department_id');
        });
    }

    public function down(): void
    {
        Schema::table('agent_applications', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'specialization']);
        });
    }
};
