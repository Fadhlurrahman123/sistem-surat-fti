<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama');
            $table->string('npm')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('jenis_surat')->nullable();
            $table->string('semester')->nullable();
            $table->string('tahun_akademik')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('ttd')->nullable(); // path tanda tangan jika diupload
            $table->string('file_pdf')->nullable(); // URL atau nama file
            $table->string('status')->default('Menunggu');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuan');
    }
};
