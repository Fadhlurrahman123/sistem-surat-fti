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
    Schema::table('surat_pengajuan', function (Blueprint $table) {
        $table->text('alasan')->nullable();
        $table->string('nama_orangtua')->nullable();
        $table->string('nama_kaprodi')->nullable();
        $table->string('ttd_orangtua')->nullable();
        $table->string('ttd_kaprodi')->nullable();
    });
}

public function down()
{
    Schema::table('surat_pengajuan', function (Blueprint $table) {
        $table->dropColumn([
            'alasan',
            'nama_orangtua',
            'nama_kaprodi',
            'ttd_orangtua',
            'ttd_kaprodi'
        ]);
    });
}

};
