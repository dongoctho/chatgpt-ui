<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('modals', 'models');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('models', 'modals');
    }
};
