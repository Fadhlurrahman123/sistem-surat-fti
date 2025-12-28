@extends('home')

@section('content')
<div class="container mx-auto mt-8 bg-white shadow-md p-8 rounded-lg w-[800px]">

    <h2 class="text-2xl font-semibold mb-6">Ajukan Surat</h2>

    <form action="{{ route('surat.persetujuan-aktif.store') }}" method="POST" enctype="multipart/form-data">



        @csrf

        <label class="block mb-2">Nama Mahasiswa</label>
        <input type="text" name="nama" value="{{ Auth::user()->username }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

        <label class="block mb-2">NPM Mahasiswa</label>
        <input type="text" name="npm" value="{{ Auth::user()->serial_number }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

        <label class="block mb-2">Program Studi</label>
        <input type="text" name="program_studi" value="{{ Auth::user()->study_program ?? 'undefined' }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

        <label class="block mb-2">Nama Kaprodi</label>
        <input type="text" name="nama_kaprodi" class="w-full p-2 border rounded mb-3 ">

        <input type="hidden" name="jenis_surat" value="{{ $jenisFull }}">


        <label class="block mb-2">Tanggal</label>
        <input type="date" name="tanggal" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Tanggal Pengajuan</label>
        <input type="date" name="tanggal_pengajuan" class="w-full p-2 border rounded mb-3" required>

        <label class="block mb-2">Nominal Pembayaran</label>
        <input type="text" name="nominal_pembayaran" class="w-full p-2 border rounded mb-3" placeholder="Rp.1.000.000" required>

        <div class="flex justify-end gap-2">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Ajukan</button>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-700 transition">Kembali</a>
        </div>
    </form>

</div>
@endsection
