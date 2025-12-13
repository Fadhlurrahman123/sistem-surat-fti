<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratPengajuan;

class SuratAktifController extends Controller
{
    
    public function create()
{
    return view('surat.aktif.create', [
        'jenis' => 'aktif',
        'jenisFull' => 'Surat Keterangan Aktif'
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
        ]);

        $surat = SuratPengajuan::create([
            'user_id'        => Auth::id(),
            'nama'           => Auth::user()->name,
            'npm'            => Auth::user()->npm,
            'program_studi'  => Auth::user()->program_studi,
            'jenis_surat'    => "Surat Keterangan Aktif",
            'semester'       => $request->semester,
            'tahun_akademik' => "{$request->tahun_akademik1}/{$request->tahun_akademik2}",
            'tanggal'        => $request->tanggal,
            'ttd_mahasiswa'  => $request->file('ttd_mahasiswa')->store('tanda_tangan', 'public'),
            'status'         => 'Menunggu'
        ]);

        $this->sendToAppScriptAktif($surat);

        return redirect()->route('surat.preview', $surat->id);
    }


    public function sendToAppScriptAktif($surat)
    {
        $scriptUrl = "https://script.google.com/macros/s/AKfycbxOb-KdkaxW8d0U340e0hOzO7YdZuHkgKQxCCyEDHj0wFMHaRgVdWXzwmA6y2JVIG8q/exec";

        $tahun = explode('/', $surat->tahun_akademik);

        $payload = [
            "id"       => $surat->id,
            "jenis"    => $surat->jenis_surat,
            "nama"     => $surat->nama,
            "npm"      => $surat->npm,
            "prodi"    => $surat->program_studi,
            "semester" => $surat->semester,
            "tahun1" => $tahun[0],
            "tahun2" => $tahun[1],
            "tanggal"  => $surat->tanggal,
            "ttd_mahasiswa"  => asset('storage/' . $surat->ttd),

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
