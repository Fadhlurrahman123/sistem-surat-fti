<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SuratPengajuan;
use Illuminate\Support\Facades\Log;

class SuratKeteranganAktifController extends Controller
{
    /**
     * FORM CREATE SURAT KETERANGAN AKTIF
     */

    public function create()
    {
        return view('surat.keterangan-aktif.create', [
            'jenis' => 'keterangan-aktif',
            'jenisFull' => 'Surat Keterangan Aktif'
        ]);
    }

    /**
     * SIMPAN & GENERATE SURAT
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'semester'     => 'required|string',
            'tahun_akademik1' => 'required|numeric',
            'tahun_akademik2' => 'required|numeric',
            'ttd_kaprodi'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'nama_kaprodi'    => 'required|string',
        ]);

        // Tentukan kode prodi
        $prodiFull = Auth::user()->study_program;
        if (str_contains(strtolower($prodiFull), 'informatika')) {
            $kodeProdi = 'TI';
        } elseif (str_contains(strtolower($prodiFull), 'perpustakaan') || str_contains(strtolower($prodiFull), 'sains informasi')) {
            $kodeProdi = 'PdSi';
        } else {
            $kodeProdi = 'XX';
        }

        // Ambil nomor terakhir per prodi
        $lastSurat = SuratPengajuan::where('program_studi', $prodiFull)
            ->where('jenis_surat', 'Surat Keterangan Aktif')
            ->orderBy('id', 'desc')
            ->first();
        $nomorUrut = $lastSurat ? $lastSurat->nomor_urut + 1 : 1;

        // Format nomor surat
        $bulanRomawi = $this->bulanRomawi(now()->month);
        $tahun = now()->year;
        $no_surat = sprintf("No.%04d/%s/SKet PP.30.02/%s/%d", $nomorUrut, $kodeProdi, $bulanRomawi, $tahun);

        // SIMPAN KE DATABASE
        $surat = SuratPengajuan::create([
            'user_id'       => Auth::id(),
            'nama'          => Auth::user()->username,
            'npm'           => Auth::user()->serial_number,
            'program_studi' => Auth::user()->study_program,
            'jenis_surat'   => 'Surat Keterangan Aktif',
            'tanggal'       => $request->tanggal,
            'semester'      => $request->semester,
            'tahun_akademik1' => $request->tahun_akademik1,
            'tahun_akademik2' => $request->tahun_akademik2,
            'ttd_kaprodi'    => $request->hasFile('ttd_kaprodi')
                ? $request->file('ttd_kaprodi')->store('tanda_tangan', 'public')
                : null,
            'nama_kaprodi'  => $request->nama_kaprodi,
            'nomor_urut'    => $nomorUrut,
            'no_surat'      => $no_surat,
            'status'        => 'Menunggu',
        ]);

        // KIRIM KE APPS SCRIPT
        $this->sendToAppScriptKeteranganAktif($surat);

        return redirect()->route('surat.preview', $surat->id);
    }

    /**
     * Helper bulan ke Romawi
     */
    private function bulanRomawi($bulan)
    {
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[$bulan - 1];
    }

    /**
     * KIRIM DATA KE GOOGLE APPS SCRIPT
     */
    public function sendToAppScriptKeteranganAktif($surat)
    {
        $scriptUrl = "https://script.google.com/macros/s/AKfycbyldVFkmqRTXYR1864ZiKpO-92U0G0WjdU8hVrOEtWpuHm_tMlXtQuXTF2Iow5ofMrO/exec"; // ganti sesuai Apps Script

        $payload = [
            "id"             => $surat->id,
            "no_surat"       => $surat->no_surat,
            "jenis"          => $surat->jenis_surat,
            "nama"           => $surat->nama,
            "npm"            => $surat->npm,
            "prodi"          => $surat->program_studi,
            "semester"       => $surat->semester,
            "tahun_akademik1" => $surat->tahun_akademik1,
            "tahun_akademik2" => $surat->tahun_akademik2,
            "tanggal"        => $surat->tanggal,
            "ttd_kaprodi"    => asset('storage/' . $surat->ttd_kaprodi),
            "nama_kaprodi"   => $surat->nama_kaprodi,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($scriptUrl, $payload);

        if ($response->successful()) {
            $result = $response->json();
            if (!empty($result['pdf_url'])) {
                $surat->update(['file_pdf' => $result['pdf_url']]);
            }
        }
    }
}
