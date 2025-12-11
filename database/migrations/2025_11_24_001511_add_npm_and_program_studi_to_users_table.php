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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'npm')) {
                $table->string('npm')->nullable();
            }

            if (!Schema::hasColumn('users', 'program_studi')) {
                $table->string('program_studi')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'npm')) {
                $table->dropColumn('npm');
            }

            if (Schema::hasColumn('users', 'program_studi')) {
                $table->dropColumn('program_studi');
            }
        });
    }
};
