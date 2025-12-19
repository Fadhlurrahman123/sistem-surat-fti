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
                <span class="font-medium">{{ Auth::user()->dispaly_name ?? 'User' }}</span>
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

        @yield('content')

    </main>

</body>

</html>