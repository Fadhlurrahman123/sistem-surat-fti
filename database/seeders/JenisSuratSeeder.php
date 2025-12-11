<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surat')->insert([
            ['nama_surat' => 'Surat Keterangan Aktif'],
            ['nama_surat' => 'Surat Penundaan Pembayaran'],
            ['nama_surat' => 'Surat Cuti Akademik'],
            ['nama_surat' => 'Surat Rekomendasi Beasiswa'],
        ]);
    }
}
