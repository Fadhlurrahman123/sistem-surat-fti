<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->nullable();
            $table->string('nama')->nullable();
            $table->string('npm')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('jenis_surat')->nullable();
            $table->string('semester')->nullable();
            $table->string('tahun_akademik')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('alasan')->nullable();
            $table->string('nama_orangtua')->nullable();
            $table->string('nama_kaprodi')->nullable();
            $table->string('ttd_mahasiswa')->nullable(); // path tanda tangan jika diupload
            $table->string('ttd_orangtua')->nullable(); // path tanda tangan jika diupload
            $table->string('ttd_kaprodi')->nullable(); // path tanda tangan jika diupload
            $table->string('file_pdf')->nullable(); // URL atau nama file
            $table->date('tanggal_pengajuan')->nullable();
            $table->string('nominal_pembayaran')->nullable();
            $table->integer('nomor_urut')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('status')->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pengajuan');
    }
};
