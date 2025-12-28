<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SuratPengajuan;
use App\Models\User;


class SuratAktifController extends Controller
{
    protected $drive;


    public function create()
    {
        return view('surat.aktif.create', [
            'jenis' => 'aktif',
            'jenisFull' => 'Surat Permohonan Aktif'
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


        // 3️⃣ Simpan FILE ID ke DB
        $surat = SuratPengajuan::create([
            'user_id'        => Auth::id(),
            'nama'           => Auth::user()->username,
            'npm'            => Auth::user()->serial_number,
            'program_studi'  => Auth::user()->study_program,
            'jenis_surat'    => "Surat Permohonan Aktif",
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
        $scriptUrl = "https://script.google.com/macros/s/AKfycbzprezP8dxrF2wrKQiflK83DB4l0q8ZvnrCl93UXOi2mbILATYVDM5w6L5qHXwclB6I/exec";

        [$tahun1, $tahun2] = explode('/', $surat->tahun_akademik);

        $payload = [
            "id"             => $surat->id,
            "jenis"          => $surat->jenis_surat,
            "nama"           => $surat->nama,
            "npm"            => $surat->npm,
            "prodi"          => $surat->program_studi,
            "semester"       => $surat->semester,
            "tahun1"         => $tahun1,
            "tahun2"         => $tahun2,
            "tanggal"        => $surat->tanggal,
            "ttd_mahasiswa"    => asset('storage/app/public/tanda_tangan/' . $surat->ttd),
        ];

        $response = Http::post($scriptUrl, $payload);

        if ($response->successful()) {
            if ($pdf = $response->json('pdf_url')) {
                $surat->update(['file_pdf' => $pdf]);
            }
        }
    }
}
