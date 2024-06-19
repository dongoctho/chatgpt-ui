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
        Schema::table('threads', function (Blueprint $table) {
            
            $table->renameColumn('modal_id', 'model_id');
            $table->renameColumn('thread_id', 'oa_thread_id');
            
            $table->dropForeign(['modal_id']);
            $table->foreign('model_id')->references('id')->on('models');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->renameColumn('model_id', 'modal_id');
            $table->renameColumn('oa_thread_id', 'thread_id');

            $table->dropForeign(['model_id']);
            $table->foreign('modal_id')->references('id')->on('models');
        });
    }
};
