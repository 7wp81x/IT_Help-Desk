<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('content');
            $table->boolean('is_internal')->default(false);
            $table->timestamps();
            
            $table->index(['ticket_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};