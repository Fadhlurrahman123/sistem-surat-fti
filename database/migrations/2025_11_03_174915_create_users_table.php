<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('displayname')->nullable();
            $table->string('email')->nullable();
            $table->string('emailyarsi')->unique();
            $table->string('password')->nullable();
            $table->string('telephonenumber')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('study_program')->nullable();
            $table->string('faculty')->nullable();
            $table->enum('role', ['M','D','K','DK','TU']); //mahasiswa dan dosen
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
