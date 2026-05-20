<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Legacy migration retained for history, but it no longer adds the deprecated specializations field.
    }

    public function down(): void
    {
        // This migration intentionally does nothing.
    }
};
