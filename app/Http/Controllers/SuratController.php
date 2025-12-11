<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\SuratPengajuan;

class SuratController extends Controller
{
    /* ============================
       HALAMAN CREATE
       ============================ */
    public function create($jenis)
{
    if ($jenis == 'aktif') {
        $jenisFull = "Surat Keterangan Aktif";
    } elseif ($jenis == 'cuti') {
        $jenisFull = "Surat Cuti Akademik";
    } else {
        abort(404);
    }

    return view('surat.' . $jenis . '.create', compact('jenisFull'));

}

public function store(Request $request, $jenis)
{
    // Simpan data ke database
    $data = SuratPengajuan::create([
        'user_id' => Auth::id(),
        'jenis_surat' => $jenis,
        'nama' => $request->nama,
        'npm' => $request->npm,
        'prodi' => $request->prodi ?? null,
        'semester' => $request->semester ?? null,
        'tahun_akademik1' => $request->tahun_akademik1 ?? null,
        'tahun_akademik2' => $request->tahun_akademik2 ?? null,
    ]);

    return redirect()->route('surat.preview', $data->id);
}





    /* ============================
       LOG & PREVIEW
       ============================ */
    public function preview($id)
    {
        return view('surat.log_preview', [
            'data' => SuratPengajuan::findOrFail($id)
        ]);
    }

    public function logSurat()
    {
        return view('surat.log', [
            'surat' => SuratPengajuan::where('user_id', Auth::id())->get()
        ]);
    }


    /* ============================
       DELETE
       ============================ */
    public function delete($id)
    {
        $surat = SuratPengajuan::findOrFail($id);

        foreach (['ttd', 'ttd_orangtua', 'ttd_kaprodi'] as $field) {
            if ($surat->$field && Storage::disk('public')->exists($surat->$field)) {
                Storage::disk('public')->delete($surat->$field);
            }
        }

        $surat->delete();

        return back()->with('success', 'Surat berhasil dihapus.');
    }
}
