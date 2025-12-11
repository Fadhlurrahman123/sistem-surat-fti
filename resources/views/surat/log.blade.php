<!DOCTYPE html>
<html>
<head>
    <title>Log Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

{{-- Header --}}
<header class="bg-gradient-to-r from-orange-600 to-orange-400 text-white shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
        <div class="flex items-center gap-4">
            <img src="{{ asset('logo.jpeg') }}" alt="Logo YARSI" class="h-14 bg-white rounded p-1">
            <div>
                <h1 class="text-sm">Program Studi</h1>
                <h2 class="text-2xl font-semibold -mt-1">Teknik Informatika</h2>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-medium">{{ Auth::user()->name }}</span>
            <div class="bg-white text-gray-600 rounded-full p-2 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.879 6.196a9 9 0 01-13.758 11.608z" />
                </svg>
            </div>
        </div>
    </div>
</header>

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
                <td class="px-3 py-2">{{ $s->id }}</td>
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

</body>
</html>
