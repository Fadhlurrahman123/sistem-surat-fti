<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>

    

<body>

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
            </div>
        </div>
    </header>

    <div class="container mx-auto mt-8 bg-white shadow-md p-8 rounded-lg w-[800px]">

    <h2 class="text-2xl font-semibold mb-6">Ajukan Surat</h2>

    <form action="{{ route('surat.aktif.store') }}" method="POST" enctype="multipart/form-data">


      @csrf
      

      <label class="block mb-2">Nama Mahasiswa</label>
      <input type="text" name="nama" value="{{ Auth::user()->name }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

      <label class="block mb-2">NPM Mahasiswa</label>
      <input type="text" name="npm" value="{{ Auth::user()->npm }}" readonly class="w-full p-2 border rounded mb-3 bg-gray-100">

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
        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-700 transition">Kembali</a>
        <button class="px-4 py-2 bg-green-600 text-white rounded">Ajukan</button>
      </div>
    </form>

</div>


</body>
</html>
