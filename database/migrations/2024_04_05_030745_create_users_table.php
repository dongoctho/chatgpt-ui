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
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('phone', 255);
            $table->string('avatar', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->string('address', 255)->nullable();
            $table->text('google_id')->nullable();
            $table->bigInteger('division_id')->nullable();
            $table->integer('role_id');
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('mst_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
