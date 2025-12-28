<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surat')->insert([
            ['nama_surat' => 'Surat Cuti Akademik'],
            ['nama_surat' => 'Surat Permohonan Aktif'],
            ['nama_surat' => 'Surat Persetujuan Aktif Akademik'],
            ['nama_surat' => 'Surat Keterangan Aktif'],
            ['nama_surat' => 'Surat Keterangan Pernah Kuliah'],
            ['nama_surat' => 'Surat Observasi Penelitian Skripsi'],
            ['nama_surat' => 'Surat Observasi Tugas Mata Kuliah'],
            ['nama_surat' => 'Surat Pengantar Remedial'],
            ['nama_surat' => 'Surat Pengunduran Diri'],
            ['nama_surat' => 'Surat Pernyataan Tidak Menerima Beasiswa'],
            ['nama_surat' => 'Surat Rekomendasi Magang'],
            ['nama_surat' => 'Surat Rekomendasi Beasiswa'],
        ]);
    }
}
