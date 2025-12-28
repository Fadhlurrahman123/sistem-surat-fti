<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Preview Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    {{-- Header --}}
    <header class="bg-gradient-to-r from-orange-600 to-orange-400 text-white shadow">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('logo.jpeg') }}" alt="Logo YARSI" class="h-14 bg-white rounded p-1">
                <div>
                    <h1 class="text-sm">Fakultas</h1>
                    <h2 class="text-2xl font-semibold -mt-1">Teknologi Informasi</h2>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-medium">{{ Auth::user()->username ?? 'User' }}</span>
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

    {{-- Content --}}
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

        <h2 class="text-xl font-semibold mb-4">Preview Surat</h2>

        {{-- Detail Surat --}}
        <table class="w-full text-left mb-6 border border-gray-300 rounded overflow-hidden">
            <tr>
                <th class="p-2 bg-gray-200 w-1/3 font-medium">No Surat</th>
                <td class="p-2">{{ $surat->no_surat }}</td>
            </tr>

            <tr>
                <th class="p-2 bg-gray-200 font-medium">Jenis Surat</th>
                <td class="p-2">{{ $surat->jenis_surat }}</td>
            </tr>

            <tr>
                <th class="p-2 bg-gray-200 font-medium">NPM</th>
                <td class="p-2">{{ $surat->npm }}</td>
            </tr>

            <tr>
                <th class="p-2 bg-gray-200 font-medium">Nama</th>
                <td class="p-2">{{ $surat->nama }}</td>
            </tr>

            <tr>
                <th class="p-2 bg-gray-200 font-medium">Waktu Pengajuan</th>
                <td class="p-2">{{ $surat->created_at->format('H:i - d/m/Y') }}</td>
            </tr>

            <tr>
                <th class="p-2 bg-gray-200 font-medium">Status</th>
                <td class="p-2">
                    @php
                    $warna = match($surat->status) {
                    'Disetujui' => 'bg-green-600',
                    'Ditolak' => 'bg-red-600',
                    default => 'bg-yellow-500'
                    };
                    @endphp

                    <span class="px-3 py-1 rounded text-white {{ $warna }}">
                        {{ $surat->status }}
                    </span>

                </td>
            </tr>
        </table>

        {{-- Prepare PDF URLs --}}
        @php
        $iframeSrc = null;
        $downloadHref = null;

        if (!empty($surat->file_pdf)) {

        $fid = null;

        // 1. Format: uc?export=download&id=XXXX
        if (Str::contains($surat->file_pdf, 'uc?export=download')) {
        parse_str(parse_url($surat->file_pdf, PHP_URL_QUERY), $q);
        $fid = $q['id'] ?? null;
        }

        // 2. Format: /file/d/XXXX
        elseif (preg_match('#/d/([^/]+)#', $surat->file_pdf, $m)) {
        $fid = $m[1];
        }

        // 3. Kalau sudah preview
        if ($fid) {
        $iframeSrc = "https://drive.google.com/file/d/{$fid}/preview";
        $downloadHref = "https://drive.google.com/uc?export=download&id={$fid}";
        }
        }
        @endphp

        {{-- PDF Preview --}}
        @if ($iframeSrc)
        <iframe
            src="{{ $iframeSrc }}"
            class="w-full h-[650px] border rounded"
            allow="autoplay">
        </iframe>
        @else
        <div class="p-4 bg-red-100 text-red-600 rounded">
            PDF tidak dapat ditampilkan
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 mt-6">

            {{-- KAPRODI --}}
            @if (
                Auth::user()->role === 'K' &&
                $surat->status === 'Menunggu Kaprodi'
            )
                <form action="{{ route('kaprodi.approve', $surat->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Setujui surat ini?')"
                        class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Setujui
                    </button>
                </form>

                <button type="button"
                    onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                    class="px-5 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Tolak
                </button>
            @endif

            {{-- KEMBALI --}}
            <a href="{{ route('kaprodi.dashboard') }}"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                Kembali
            </a>

        </div>


        {{-- Reject Modal --}}
        <div id="rejectModal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

                <h3 class="text-lg font-semibold mb-4 text-red-600">
                    Konfirmasi Penolakan
                </h3>

                <p class="mb-6 text-gray-600">
                    Apakah Anda yakin ingin menolak surat ini?
                </p>

                <form action="{{ route('kaprodi.reject', $surat->id) }}" method="POST">
                    @csrf

                    <div class="flex justify-end gap-3">
                        <button type="button"
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Tolak Surat
                        </button>
                    </div>
                </form>

            </div>
        </div>



</body>

</html>
