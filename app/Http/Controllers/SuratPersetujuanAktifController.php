<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SuratPengajuan;
use App\Models\User;
use Carbon\Carbon;

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
        try {
            $validated = $request->validate([
                'nama' => 'required|string',
                'npm' => 'required|string',
                'nama_kaprodi'   => 'required|string',
                'tanggal'         => 'required|date',
                'tanggal_pengajuan'         => 'required|date',
                'nominal_pembayaran' => 'required|string',
            ]);

            $surat = SuratPengajuan::create([
                'user_id' => Auth::id(),
                'nama'           => $validated['nama'],
                'npm'            => $validated['npm'],
                'jenis_surat'    => "Surat Persetujuan Aktif",
                'nama_kaprodi'   => $validated['nama_kaprodi'],
                'tanggal'        => $validated['tanggal'],
                'tanggal_pengajuan'        => $validated['tanggal_pengajuan'],
                'nominal_pembayaran' => $validated['nominal_pembayaran'],
            ]);
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }

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
