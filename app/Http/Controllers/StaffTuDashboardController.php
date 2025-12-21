<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\SuratPengajuan;
use App\Traits\SuratHelper;
use Illuminate\Http\Request;

class StaffTuDashboardController extends Controller
{
    public function index()
    {
        $surat = SuratPengajuan::latest()->get();

        return view('staff-tu.dashboard', compact('surat'));
    }

    public function preview($id)
    {
        $surat = SuratPengajuan::findOrFail($id);
        return view('staff-tu.preview', compact('surat'));
    }

    use SuratHelper;

    public function approve($id)
    {
        $surat = SuratPengajuan::findOrFail($id);

        // ❌ Cegah approve ulang
        if ($surat->status === 'Disetujui') {
            return back()->with('warning', 'Surat sudah disetujui');
        }

        $prodiFull = strtolower($surat->program_studi);

        if (str_contains($prodiFull, 'informatika')) {
            $kodeProdi = 'TI';
        } elseif (
            str_contains($prodiFull, 'perpustakaan') ||
            str_contains($prodiFull, 'sains informasi')
        ) {
            $kodeProdi = 'PdSi';
        } else {
            $kodeProdi = 'XX';
        }


        // ==========================
        // GENERATE NO SURAT
        // ==========================
        $bulanRomawi = $this->bulanRomawi(now()->month);
        $tahun = now()->year;

        $last = SuratPengajuan::whereYear('created_at', $tahun)
            ->where('jenis_surat', $surat->jenis_surat)
            ->where('program_studi', $surat->program_studi)
            ->whereNotNull('no_surat')
            ->orderBy('nomor_urut', 'desc')
            ->first();

        $nomorUrut = $last ? $last->nomor_urut + 1 : 1;

        $no_surat = sprintf(
            "%04d/%s/SKet PP.30.02/%s/%d",
            $nomorUrut,
            $kodeProdi,
            $bulanRomawi,
            $tahun
        );



        // ==========================
        // UPDATE DATABASE
        // ==========================
        $surat->update([
            'status' => 'Disetujui',
            'nomor_urut' => $nomorUrut,
            'no_surat' => $no_surat,
            'catatan_tu' => null
        ]);

        // ==========================
        // GENERATE ULANG PDF
        // ==========================
        $this->generatePdfUlang($surat);

        return back()->with('success', 'Surat disetujui & PDF digenerate ulang');
    }

    private function generatePdfUlang($surat)
    {
        $scriptUrl = "https://script.google.com/macros/s/AKfycbyldVFkmqRTXYR1864ZiKpO-92U0G0WjdU8hVrOEtWpuHm_tMlXtQuXTF2Iow5ofMrO/exec";

        $payload = [
            "id" => $surat->id,
            "no_surat" => $surat->no_surat,
            "nama" => $surat->nama,
            "npm" => $surat->npm,
            "prodi" => $surat->program_studi,
            "jenis" => $surat->jenis_surat,
            "semester" => $surat->semester,
            "tahun_akademik1" => $surat->tahun_akademik1,
            "tahun_akademik2" => $surat->tahun_akademik2,
            "tanggal" => $surat->tanggal,
            "status" => "Disetujui",
        ];

        $response = Http::post($scriptUrl, $payload);

        if ($response->successful() && isset($response['pdf_url'])) {
            $surat->update([
                'file_pdf' => $response['pdf_url']
            ]);
        }
    }



    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_tu' => 'required|string'
        ]);

        $surat = SuratPengajuan::findOrFail($id);
        $surat->update([
            'status' => 'Ditolak',
            'catatan_tu' => $request->catatan_tu
        ]);

        return back()->with('success', 'Surat berhasil ditolak');
    }
}
