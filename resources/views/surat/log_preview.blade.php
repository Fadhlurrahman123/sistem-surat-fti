@extends('home')

@section('content')
<div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-semibold mb-4">Preview Surat</h2>

    {{-- Detail Surat --}}
    <table class="w-full text-left mb-6 border border-gray-300 rounded overflow-hidden">
        <tr>
            <th class="p-2 bg-gray-200 w-1/3 font-medium">No Surat</th>
            <td class="p-2">{{ $data->no_surat }}</td>
        </tr>

        <tr>
            <th class="p-2 bg-gray-200 font-medium">Jenis Surat</th>
            <td class="p-2">{{ $data->jenis_surat }}</td>
        </tr>

        <tr>
            <th class="p-2 bg-gray-200 font-medium">NPM</th>
            <td class="p-2">{{ $data->npm }}</td>
        </tr>

        <tr>
            <th class="p-2 bg-gray-200 font-medium">Nama</th>
            <td class="p-2">{{ $data->nama }}</td>
        </tr>

        <tr>
            <th class="p-2 bg-gray-200 font-medium">Waktu Pengajuan</th>
            <td class="p-2">{{ $data->created_at->format('H:i - d/m/Y') }}</td>
        </tr>

        <tr>
            <th class="p-2 bg-gray-200 font-medium">Status</th>
            <td class="p-2">
                @php
                $status = $data->status ?? 'Menunggu';
                $statusClass = $status === 'Menunggu' ? 'bg-yellow-500' : 'bg-green-600';
                @endphp
                <span class="px-3 py-1 rounded text-white {{ $statusClass }}">{{ $status }}</span>
            </td>
        </tr>
    </table>

    {{-- Prepare PDF URLs --}}
    @php
    $iframeSrc = null;
    $downloadHref = null;

    if (!empty($data->file_pdf)) {

    $fid = null;

    // 1. Format: uc?export=download&id=XXXX
    if (Str::contains($data->file_pdf, 'uc?export=download')) {
    parse_str(parse_url($data->file_pdf, PHP_URL_QUERY), $q);
    $fid = $q['id'] ?? null;
    }

    // 2. Format: /file/d/XXXX
    elseif (preg_match('#/d/([^/]+)#', $data->file_pdf, $m)) {
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

    {{-- Tombol --}}
    <div class="flex justify-end mt-6 gap-3">

        @if ($downloadHref)
        <a href="{{ $downloadHref }}" target="_blank"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Download PDF
        </a>
        @endif

        <a href="{{ route('surat.log') }}"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
            Kembali
        </a>
    </div>

</div>
@endsection