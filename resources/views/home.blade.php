<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Form Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

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
                <span class="font-medium">{{ Auth::user()->name ?? 'User' }}</span>
                <div class="bg-white text-gray-600 rounded-full p-2 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.879 6.196a9 9 0 01-13.758 11.608z" />
                    </svg>
                </div>

                <!-- Logout button -->
                <a href="{{ route('logout') }}"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
                Logout
                </a>
            </div>
        </div>
    </header>

    {{-- Konten utama --}}
    <main class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h10l6-6V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                </svg>
                Daftar Form Surat
            </h3>
            <form action="" class="flex items-center gap-2">
                <input type="text" placeholder="Search..." class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-orange-200">
                <a 
                href="{{ route('surat.log') }}" 
                class="bg-gray-200 text-gray-700 px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h6m-3-3v6M3 6h12M3 12h9m-9 6h12" />
                </svg>
                Log Surat
            </a>
            </form>
        </div>

        {{-- Daftar Surat --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($jenis_surat as $surat)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col justify-between p-5">
            <div>
                <div class="flex justify-center text-gray-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2h-2.5L12 4 9.5 5H7a2 2 0 00-2 2v3a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-center text-lg font-semibold mb-4">{{ $surat->nama_surat }}</h3>
            </div>

                @php
                    $route = match($surat->nama_surat) {
                        'Surat Keterangan Aktif' => route('surat.aktif.create'),
                        'Surat Cuti Akademik' => route('surat.cuti.create'),
                        'Surat Persetujuan Aktif Akademik' => route('surat.persetujuan-aktif.create'),
                        default => route('surat.aktif.create'),
                    };

                @endphp

                <a href="{{ $route }}"
                class="w-full bg-green-500 text-white font-semibold py-2 rounded flex items-center justify-center gap-2 hover:bg-green-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajukan Surat
            </a>
            <img src="{{ asset('storage/1CfsNjsWm7O4fQksCCrXfqfHvI4iVICA7J66hWWw.jpg' . $surat->ttd) }}" alt="TTD Mahasiswa" style="width:200px; height:auto;">

        </div>
    @endforeach
</div>

    </main>

</body>
</html>
