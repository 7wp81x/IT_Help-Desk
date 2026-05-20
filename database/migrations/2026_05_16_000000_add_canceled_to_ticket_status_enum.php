<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `tickets` MODIFY `status` ENUM('open', 'in_progress', 'pending', 'resolved', 'closed', 'canceled') NOT NULL DEFAULT 'open'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `tickets` MODIFY `status` ENUM('open', 'in_progress', 'pending', 'resolved', 'closed') NOT NULL DEFAULT 'open'");
    }
};
