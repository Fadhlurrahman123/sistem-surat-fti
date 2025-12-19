<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;

class HomeController extends Controller
{
    public function index()
    {
        $jenis_surat = JenisSurat::all(); // ambil semua data dari tabel jenis_surat
        return view('surat.daftar_surat', compact('jenis_surat')); // kirim ke view home.blade.php
    }
}
