<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Traits\SuratHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffTuDashboardController extends Controller
{
    use SuratHelper;

    // ==========================
    // DASHBOARD
    // ==========================
    public function index()
    {
        $surat = SuratPengajuan::latest()->get(); // TANPA where status
        return view('staff-tu.dashboard', compact('surat'));
    }



    // ==========================
    // PREVIEW
    // ==========================
    public function preview($id)
    {
        $surat = SuratPengajuan::findOrFail($id);
        return view('staff-tu.preview', compact('surat'));
    }

    // ==========================
    // APPROVE SURAT
    // ==========================
    public function approve($id)
    {
        $surat = SuratPengajuan::findOrFail($id);

        // ❌ Kaprodi belum validasi
        if ($surat->status !== 'Menunggu TU') {
            return back()->with('error', 'Surat belum divalidasi Kaprodi');
        }



        // ❌ Cegah approve ulang TU
        if ($surat->status === 'Disetujui') {
            return back()->with('warning', 'Surat sudah disetujui');
        }

        $noSurat = null;
        $nomorUrut = null;

        // ==================================================
        // HANYA SURAT KETERANGAN AKTIF YANG DAPAT NO & PDF
        // ==================================================
        if ($surat->jenis_surat === 'Surat Keterangan Aktif') {

            $prodi = strtolower($surat->program_studi);

            if (str_contains($prodi, 'informatika')) {
                $kodeProdi = 'TI';
            } elseif (
                str_contains($prodi, 'perpustakaan') ||
                str_contains($prodi, 'sains informasi')
            ) {
                $kodeProdi = 'PdSi';
            } else {
                $kodeProdi = 'XX';
            }

            $bulanRomawi = $this->bulanRomawi(now()->month);
            $tahun = now()->year;

            $last = SuratPengajuan::whereYear('created_at', $tahun)
                ->where('jenis_surat', 'Surat Keterangan Aktif')
                ->where('program_studi', $surat->program_studi)
                ->whereNotNull('no_surat')
                ->orderBy('nomor_urut', 'desc')
                ->first();

            $nomorUrut = $last ? $last->nomor_urut + 1 : 1;

            $noSurat = sprintf(
                "%04d/%s/SKet PP.30.02/%s/%d",
                $nomorUrut,
                $kodeProdi,
                $bulanRomawi,
                $tahun
            );
        }

        // ==========================
        // UPDATE DATABASE
        // ==========================
        $surat->update([
            'status' => 'Disetujui',
            'nomor_urut' => $nomorUrut,
            'no_surat' => $noSurat,
            'catatan_tu' => null
        ]);

        // ==========================
        // GENERATE PDF (SK AKTIF)
        // ==========================
        if ($surat->jenis_surat === 'Surat Keterangan Aktif') {
            $this->generatePdfUlang($surat);
        }

        return back()->with('success', 'Surat berhasil disetujui TU');
    }

    // ==========================
    // GENERATE PDF ULANG (SK AKTIF SAJA)
    // ==========================
    private function generatePdfUlang(SuratPengajuan $surat)
    {
        // Pengaman
        if ($surat->jenis_surat !== 'Surat Keterangan Aktif') {
            return;
        }

        $scriptUrl = "https://script.google.com/macros/s/AKfycbyldVFkmqRTXYR1864ZiKpO-92U0G0WjdU8hVrOEtWpuHm_tMlXtQuXTF2Iow5ofMrO/exec";

        $payload = [
            "id"             => $surat->id,
            "no_surat"       => $surat->no_surat,
            "jenis"          => $surat->jenis_surat,
            "nama"           => $surat->nama,
            "npm"            => $surat->npm,
            "prodi"          => $surat->program_studi,
            "semester"       => $surat->semester,
            "tahun_akademik1"  => $tahun[0] ?? '',
            "tahun_akademik2"  => $tahun[1] ?? '',
            "tanggal"        => $surat->tanggal,
            "nama_kaprodi"   => $surat->nama_kaprodi,

        ];

        $response = Http::post($scriptUrl, $payload);

        if ($response->successful() && isset($response['pdf_url'])) {
            $surat->update([
                'file_pdf' => $response['pdf_url']
            ]);
        }
    }

    // ==========================
    // REJECT SURAT
    // ==========================
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

    // ==========================
    // ARSIPKAN SURAT
    // ==========================
    public function arsipkan($id)
    {
        $surat = SuratPengajuan::findOrFail($id);

        if ($surat->status !== 'Disetujui') {
            return back()->with('error', 'Surat belum disetujui');
        }

        if ($surat->archived_at) {
            return back()->with('warning', 'Surat sudah diarsipkan');
        }

        Http::post(
            "https://script.google.com/macros/s/AKfycbxT42B_D4mlXyTkywIbZxgZYg0q9VJ0yXagqSXspuHe9nHXNFN7uYl3gDR6BGXeUGXL/exec",
            [
                'no_surat' => $surat->no_surat,
                'jenis_surat' => $surat->jenis_surat,
                'prodi' => $surat->program_studi,
                'npm' => $surat->npm,
                'nama' => $surat->nama,
            ]
        );

        $surat->update([
            'archived_at' => now()
        ]);

        return back()->with('success', 'Surat berhasil diarsipkan');
    }
}
