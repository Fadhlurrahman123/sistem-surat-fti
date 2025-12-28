@extends('home')

@section('content')
<div class="container mx-auto mt-8 bg-white shadow-md p-8 rounded-lg w-[800px]">

    <h2 class="text-2xl font-semibold mb-6">Ajukan Surat</h2>

    <form action="{{ route('surat.aktif.store') }}" method="POST" enctype="multipart/form-data">


        @csrf


        <label class="block mb-2">Nama Mahasiswa</label>
        <input type="text" name="nama" value="{{ Auth::user()->username }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

        <label class="block mb-2">NPM Mahasiswa</label>
        <input type="text" name="npm" value="{{ Auth::user()->serial_number }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

        <input type="hidden" name="jenis_surat" value="{{ $jenisFull }}">



        <label class="block mb-2">Semester</label>
        <select name="semester" class="w-full p-2 border rounded mb-3" required>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
        </select>

        <label class="block mb-2">Tahun Akademik</label>
        <div class="flex gap-2 mb-3">
            <input type="number" name="tahun_akademik1" placeholder="2025" class="w-1/2 p-2 border rounded" required>
            <input type="number" name="tahun_akademik2" placeholder="2026" class="w-1/2 p-2 border rounded" required>
        </div>

        <label class="block mb-2">Tanggal</label>
        <input type="date" name="tanggal" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Upload Tanda Tangan</label>
        <input type="file" name="ttd_mahasiswa" class="w-full mb-4" required>

        <div class="flex justify-end gap-2">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Ajukan</button>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-700 transition">Kembali</a>
        </div>
    </form>

</div>
@endsection
