<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SuratPengajuan;
use Illuminate\Http\Request;

class KaprodiDashboardController extends Controller
{
    // ==========================
    // DASHBOARD KAPRODI
    // ==========================
    public function index()
    {
        $user = Auth::user(); // Kaprodi yang login
        $surat = SuratPengajuan::where('program_studi', $user->study_program)
                                ->latest()
                                ->get();

        return view('kaprodi.dashboard', compact('surat'));
    }


    // ==========================
    // PREVIEW SURAT
    // ==========================
    public function preview($id)
    {
        $surat = SuratPengajuan::findOrFail($id);

        return view('kaprodi.preview', compact('surat'));
    }

    // ==========================
    // SETUJUI (VALIDASI KAPRODI)
    // ==========================
    public function approve($id)
    {
        $surat = SuratPengajuan::findOrFail($id);

        // Cegah approve ulang
        if ($surat->validasi_kaprodi) {
            return back()->with('warning', 'Surat sudah divalidasi Kaprodi');
        }

        $surat->update([
            'status' => 'Menunggu TU'
        ]);

        return back()->with('success', 'Surat berhasil divalidasi Kaprodi');
    }

    // ==========================
    // TOLAK SURAT
    // ==========================
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kaprodi' => 'required|string'
        ]);

        $surat = SuratPengajuan::findOrFail($id);

        $surat->update([
            'status' => 'Ditolak Kaprodi',
            'catatan_kaprodi' => $request->catatan_kaprodi
        ]);

        return back()->with('success', 'Surat berhasil ditolak Kaprodi');
    }
}
