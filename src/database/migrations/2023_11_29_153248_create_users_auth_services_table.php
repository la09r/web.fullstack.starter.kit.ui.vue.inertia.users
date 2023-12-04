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
        Schema::dropIfExists('users_auth_services');
        Schema::create('users_auth_services', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->string('service_id', 255);

            $table->string('login', 255)->nullable();
            $table->tinyText('secrets')->nullable();

            $table->text('data')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->tinyText('avatar')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->primary(['user_id', 'service_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_auth_services');
    }
};
