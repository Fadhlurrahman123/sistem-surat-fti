@extends('home')

@section('content')
<div class="container mx-auto mt-8 bg-white shadow-md p-8 rounded-lg w-[800px]">

    <h2 class="text-2xl font-semibold mb-6">Ajukan Surat Keterangan Aktif</h2>

    <form action="{{ route('surat.keterangan-aktif.store') }}"
        method="POST"
        enctype="multipart/form-data"
        onsubmit="disableForm(this)">
        @csrf

        {{-- Nama --}}
        <label class="block mb-2">Nama Mahasiswa</label>
        <input type="text"
            name="nama"
            value="{{ Auth::user()->username }}"
            readonly
            class="w-full p-2 border rounded mb-3 bg-gray-100">

        {{-- NPM --}}
        <label class="block mb-2">NPM</label>
        <input type="text"
            name="npm"
            value="{{ Auth::user()->serial_number }}"
            readonly
            class="w-full p-2 border rounded mb-3 bg-gray-100">

        {{-- Semester --}}
        <label class="block mb-2">Semester</label>
        <select name="semester" class="w-full p-2 border rounded mb-3" required>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
        </select>

        {{-- Tahun Akademik --}}
        <label class="block mb-2">Tahun Akademik</label>
        <div class="flex gap-2 mb-3">
            <input type="number" name="tahun_akademik1" placeholder="2025" class="w-1/2 p-2 border rounded" required>
            <input type="number" name="tahun_akademik2" placeholder="2026" class="w-1/2 p-2 border rounded" required>
        </div>

        {{-- Tanggal --}}
        <label class="block mb-2">Tanggal Surat</label>
        <input type="date"
            name="tanggal"
            class="w-full p-2 border rounded mb-3"
            required>

        {{-- Nama Kaprodi --}}
        <label class="block mb-2">Nama Kaprodi</label>
        <input type="text"
            name="nama_kaprodi"
            class="w-full p-2 border rounded mb-3"
            required>

        {{-- Action --}}
        <div class="flex justify-end gap-2">
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Ajukan
            </button>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-700 transition">Kembali</a>
        </div>
    </form>

    <script>
        function disableForm(form) {
            const btn = form.querySelector('#submitBtn');
            btn.disabled = true;
            btn.innerText = 'Processing...';

            form.querySelectorAll('input, select, textarea, button')
                .forEach(el => el.readOnly = true);
        }
    </script>
</div>
@endsection
