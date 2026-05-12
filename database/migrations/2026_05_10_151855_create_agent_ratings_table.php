<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agent_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['ticket_id', 'user_id'], 'unique_ticket_user_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_ratings');
    }
};
