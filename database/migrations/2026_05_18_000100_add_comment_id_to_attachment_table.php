<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attachment', function (Blueprint $table) {
            if (!Schema::hasColumn('attachment', 'comment_id')) {
                $table->foreignId('comment_id')->nullable()->after('ticket_id')->constrained('comments')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('attachment', function (Blueprint $table) {
            if (Schema::hasColumn('attachment', 'comment_id')) {
                $table->dropConstrainedForeignId('comment_id');
            }
        });
    }
};
