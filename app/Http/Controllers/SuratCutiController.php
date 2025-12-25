<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratPengajuan;

class SuratCutiController extends Controller
{

    public function create()
    {
        return view('surat.cuti.create', [
            'jenis' => 'cuti',
            'jenisFull' => 'Surat Cuti Akademik'
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'jenis_surat'     => 'required|string',
            'semester'        => 'required|string',
            'tahun_akademik1' => 'required|numeric',
            'tahun_akademik2' => 'required|numeric',
            'tanggal'         => 'required|date',
            'ttd_mahasiswa'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'alasan'          => 'required|string',
            'nama_orangtua'   => 'required|string',
            'ttd_orangtua'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $surat = SuratPengajuan::create([
            'user_id'        => Auth::id(),
            'nama'           => Auth::user()->username,
            'npm'            => Auth::user()->serial_number,
            'program_studi'  => Auth::user()->study_program,
            'jenis_surat'    => "Surat Cuti Akademik",
            'semester'       => $request->semester,
            'tahun_akademik' => "{$request->tahun_akademik1}/{$request->tahun_akademik2}",
            'tanggal'        => $request->tanggal,
            'ttd_mahasiswa'  => $request->file('ttd_mahasiswa')->store('tanda_tangan', 'public'),
            'alasan'         => $request->alasan,
            'nama_orangtua'  => $request->nama_orangtua,
            'ttd_orangtua'   => $request->file('ttd_orangtua')->store('tanda_tangan', 'public'),
            'nama_kaprodi'   => $request->nama_kaprodi ?? null,
            'ttd_kaprodi'    => $request->hasFile('ttd_kaprodi')
                ? $request->file('ttd_kaprodi')->store('tanda_tangan', 'public')
                : null,
            'status'         => 'Menunggu'
        ]);

        $this->sendToAppScriptCuti($surat);

        return redirect()->route('surat.preview', $surat->id);
    }


    public function sendToAppScriptCuti($surat)
    {
        $scriptUrl = "https://script.google.com/macros/s/AKfycbxE0hg7hdZsQJt0hZdZLY-hVNhTNfWVio5AKopUEEQ17hHFKLxJT8Pg01HC8PaPAns1zw/exec";

        $tahun = explode('/', $surat->tahun_akademik);


        $payload = [
            "id"               => $surat->id,
            "jenis"            => $surat->jenis_surat,
            "nama"             => $surat->nama,
            "npm"              => $surat->npm,
            "prodi"            => $surat->program_studi,
            "tahun_akademik1"  => $tahun[0] ?? '',
            "tahun_akademik2"  => $tahun[1] ?? '',
            "tanggal"          => $surat->tanggal,
            "alasan"           => $surat->alasan,
            "nama_orangtua"    => $surat->nama_orangtua,
            "nama_kaprodi"     => $surat->nama_kaprodi,

            "ttd_mahasiswa"    => asset('storage/app/public/tanda_tangan/' . $surat->ttd),
            "ttd_orangtua"     => asset('storage/app/public/tanda_tangan/' . $surat->ttd_orangtua),
            "ttd_kaprodi"      => $surat->ttd_kaprodi ? asset('storage/' . $surat->ttd_kaprodi) : "",
        ];

        // // ================================
        // // 1️⃣ SIMPAN REQUEST KE FILE TXT
        // // ================================
        // $requestText =
        //     "URL: $scriptUrl\n" .
        //     "Timestamp: $timestamp\n\n" .
        //     "PAYLOAD:\n" . print_r($payload, true);

        // \Storage::disk('local')->put("logs/request_cuti_$timestamp.txt", $requestText);

        // // Pastikan folder logs ada
        // // storage/app/logs/ (Laravel akan buat otomatis)
        // // ================================


        // Kirim ke AppScript
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($scriptUrl, $payload);




        // // ================================
        // // 2️⃣ SIMPAN RESPONSE KE FILE TXT
        // // ================================
        // $responseText =
        //     "URL: $scriptUrl\n" .
        //     "Timestamp: $timestamp\n\n" .
        //     "HTTP Status: " . $response->status() . "\n\n" .
        //     "Response Body:\n" . $response->body();

        // \Storage::disk('local')->put("logs/response_cuti_$timestamp.txt", $responseText);
        // // ================================


        // ========== LOGIKA ASLI ==========
        if ($response->successful()) {
            $result = $response->json();
            if (!empty($result['pdf_url'])) {
                $surat->update(['file_pdf' => $result['pdf_url']]);
            }
        }
    }
}
