<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // Contact Info
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            
            // Role & Status
            $table->enum('role', ['admin', 'user', 'agent'])->default('user');
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // For REGULAR USERS (ticket submitters)
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            
            // For AGENTS (support staff)
            $table->string('employee_id')->nullable()->unique();
            $table->string('specialization')->nullable();
            $table->integer('tickets_handled')->default(0);
            $table->float('rating')->default(0);
            $table->text('skills')->nullable();
            
            // Tracking
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};