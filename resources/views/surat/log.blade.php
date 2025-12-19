@extends('home')

@section('content')
<div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-semibold mb-4">Daftar Surat yang Sudah Diajukan</h2>

    {{-- Notifikasi --}}
    @if (session('success'))
    <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-3 py-2 text-left">No</th>
                <th class="px-3 py-2 text-left">No Surat</th>
                <th class="px-3 py-2 text-left">Jenis Surat</th>
                <th class="px-3 py-2 text-left">NPM Mahasiswa</th>
                <th class="px-3 py-2 text-left">Nama Mahasiswa</th>
                <th class="px-3 py-2 text-left">Waktu Pengajuan</th>
                <th class="px-3 py-2 text-left">Status</th>
                <th class="px-3 py-2 text-left">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($surat as $i => $s)
            <tr onclick="window.location='{{ route('surat.preview', $s->id) }}'"
                class="cursor-pointer hover:bg-orange-100 transition">

                <td class="px-3 py-2">{{ $i + 1 }}</td>
                <td class="px-3 py-2">{{ $s->no_surat }}</td>
                <td class="px-3 py-2">{{ $s->jenis_surat }}</td>
                <td class="px-3 py-2">{{ $s->npm }}</td>
                <td class="px-3 py-2">{{ $s->nama }}</td>
                <td class="px-3 py-2">{{ $s->created_at->format('H:i - d/m/Y') }}</td>
                <td class="px-3 py-2">{{ $s->status ?? 'Menunggu' }}</td>

                <!-- Tombol Hapus -->
                <td class="px-3 py-2">
                    <form action="{{ route('surat.delete', $s->id) }}"
                        method="POST"
                        onsubmit="event.stopPropagation(); return confirm('Yakin ingin menghapus surat ini?')"
                        onclick="event.stopPropagation();">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition text-sm">
                            Hapus
                        </button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>

    {{-- Tombol kembali --}}
    <div class="mt-6">
        <a href="{{ route('home') }}"
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-700 transition">
            Home
        </a>
    </div>

</div>
@endsection