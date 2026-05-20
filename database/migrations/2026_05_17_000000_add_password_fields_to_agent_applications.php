<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agent_applications', function (Blueprint $table) {
            $table->string('generated_employee_id')->nullable()->after('status')->comment('Employee ID generated during approval');
            $table->string('generated_password')->nullable()->after('generated_employee_id')->comment('Temporary password generated during approval');
        });
    }

    public function down(): void
    {
        Schema::table('agent_applications', function (Blueprint $table) {
            $table->dropColumn(['generated_employee_id', 'generated_password']);
        });
    }
};
