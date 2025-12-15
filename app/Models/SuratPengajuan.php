<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPengajuan extends Model
{
    use HasFactory;

    protected $table = 'surat_pengajuan';

    protected $fillable = [
        'user_id',
        'nama',
        'npm',
        'program_studi',
        'jenis_surat',
        'semester',
        'tahun_akademik',
        'tanggal',
        'alasan',
        'nama_orangtua',
        'nama_kaprodi',
        'ttd_mahasiswa',
        'ttd_orangtua',
        'ttd_kaprodi',
        'file_pdf',
        'tanggal_pengajuan',
        'nominal_pembayaran',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
