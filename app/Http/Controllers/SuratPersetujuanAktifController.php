<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SuratPengajuan;

class SuratPersetujuanAktifController extends Controller
{
    /**
     * FORM CREATE
     */
    public function create()
    {
        return view('surat.persetujuan-aktif.create', [
            'jenis' => 'persetujuan-aktif',
            'jenisFull' => 'Surat Persetujuan Aktif Akademik'
        ]);
    }

    /**
     * SIMPAN & GENERATE SURAT
     */
    public function store(Request $request)
    {
        $request->validate([
        'jenis_surat'     => 'required|string',
        'tanggal_pengajuan'  => 'required|date',
        'nominal_pembayaran' => 'required|string',
        'tanggal'            => 'required|date',
        'semester'           => 'required|string',
        'tahun_akademik1'    => 'required|numeric',
        'tahun_akademik2'    => 'required|numeric',
        'ttd_kaprodi'        => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        // SIMPAN TTD KAPRODI
        $ttdPath = $request->file('ttd_kaprodi')
            ->store('tanda_tangan', 'public');

        // SIMPAN KE DATABASE
        $surat = SuratPengajuan::create([
            'user_id'              => Auth::id(),
            'nama'                 => Auth::user()->username,
            'npm'                  => Auth::user()->serial_number,
            'program_studi'        => Auth::user()->study_program,
            'jenis_surat'          => 'Surat Persetujuan Aktif Akademik',
            'tanggal'              => $request->tanggal,
            'tanggal_pengajuan'    => $request->tanggal_pengajuan,
            'nominal_pembayaran'   => $request->nominal_pembayaran,
            'ttd_kaprodi'          => $request->hasFile('ttd_kaprodi')
                                    ? $request->file('ttd_kaprodi')->store('tanda_tangan', 'public')
                                    : null,
            'nama_kaprodi'         => $request->nama_kaprodi,
            'status'               => 'Menunggu',
        ]);

        // KIRIM KE APPS SCRIPT
        $this->sendToAppScriptPersetujuanAktif($surat);

        return redirect()->route('surat.preview', $surat->id);
    }

    /**
     * KIRIM DATA KE GOOGLE APPS SCRIPT
     */
    public function sendToAppScriptPersetujuanAktif($surat)
    {
        $scriptUrl = "https://script.google.com/macros/s/AKfycbz7e76bQckzxVUaHxRA5dFz9JB8eEEZVfz8GdtxdvNp3b8IYCOfMc_UQmc-dezx_fl1YQ/exec";

        $payload = [
            "id"                 => $surat->id,
            "jenis"              => $surat->jenis_surat,
            "nama"               => $surat->nama,
            "npm"                => $surat->npm,
            "prodi"              => $surat->program_studi,
            "tanggal_pengajuan"  => $surat->tanggal_pengajuan,
            "nominal_pembayaran" => $surat->nominal_pembayaran,
            "tanggal"            => $surat->tanggal,
            "nama_kaprodi"       => $surat->nama_kaprodi,
            "ttd_kaprodi"        => asset('storage/' . $surat->ttd_kaprodi),
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
