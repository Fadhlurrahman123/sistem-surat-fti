<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisSurat;

class HomeController extends Controller
{
    public function index()
    {
        $jenis_surat = JenisSurat::all(); // ambil semua data dari tabel jenis_surat
        return view('home', compact('jenis_surat')); // kirim ke view home.blade.php
    }
}
