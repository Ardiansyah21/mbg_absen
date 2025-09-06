@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    @if(session('success'))
    <div class="mb-4 p-4 rounded bg-green-100 border border-green-400 text-green-700 flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 rounded bg-red-100 border border-red-400 text-red-700 flex justify-between items-center">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
    </div>
    @endif

    <!-- Header dengan Search + Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-blue-500">Jadwal Pemakaian Baju</h1>

        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full md:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <!-- Icon Search -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </span>
                <input type="text" id="searchInput" onkeyup="searchTable()"
                    class="w-full pl-10 pr-4 py-2 rounded-full shadow-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition"
                    placeholder="Cari nama baju atau hari...">
            </div>

            <!-- Buttons -->
            <button onclick="openModal()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah Jadwal
            </button>
            <a href="{{ route('admin.baju.export') }}" target="_blank"
                class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded shadow">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="mb-12">
        <div class="overflow-x-auto rounded-2xl shadow-md shadow-sky-200/70">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-sky-500 text-white">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama Baju</th>
                        <th class="px-6 py-3">Hari</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($bajus as $i => $baju)
                    <tr class="hover:bg-sky-50 transition">
                        <td class="px-6 py-4">{{ $i+1 }}</td>
                        <td class="px-6 py-4 font-medium">{{ $baju->nama_baju }}</td>
                        <td class="px-6 py-4">{{ $baju->hari }}</td>
                        <td class="px-6 py-4">{{ $baju->deskripsi }}</td>
                        <td class="px-6 py-4">
                            @if($baju->gambar && file_exists(public_path('assets/img/' . $baju->gambar)))
                            <img src="{{ asset('assets/img/' . $baju->gambar) }}" alt="Baju {{ $baju->nama_baju }}"
                                class="h-16 rounded">
                            @else
                            <span class="text-gray-400">Tidak ada gambar</span>
                            @endif
                        </td>


                        <td class="px-6 py-4 flex gap-2">
                            <button type="button"
                                onclick="openModalEdit({{ $baju->id }}, '{{ $baju->nama_baju }}', '{{ $baju->hari }}', '{{ $baju->deskripsi }}', '{{ $baju->gambar }}')"
                                class="px-3 py-1 bg-sky-500 text-white rounded hover:bg-sky-600">
                                Edit
                            </button>




                            <form action="{{ route('bajus.destroy',$baju->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus jadwal baju {{ $baju->nama_baju }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalForm" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl shadow-sky-500 w-full max-w-lg p-6 border-t-4 border-sky-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-sky-600">Tambah Jadwal Baju</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form action="{{ route('bajus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <!-- Nama -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nama Baju</label>
                    <input type="text" name="nama_baju" class="w-full border border-gray-300 rounded-md px-4 py-2"
                        placeholder="Masukkan Nama Baju" required>
                </div>

                <!-- Hari -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Hari</label>
                    <select name="hari" class="w-full border border-gray-300 rounded-md px-4 py-2" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-md px-4 py-2"
                        placeholder="Tambahkan deskripsi..."></textarea>
                </div>

                <!-- Gambar -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Gambar Baju</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full border border-gray-300 rounded-md px-4 py-2">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-sky-500 text-white font-semibold rounded hover:bg-sky-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl shadow-sky-500 w-full max-w-lg p-6 border-t-4 border-sky-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-sky-600">Edit Jadwal Baju</h2>
            <button type="button" onclick="closeModalEdit()"
                class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <!-- Form Edit -->
        <form id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editId" name="id">

            <div class="space-y-4">
                <!-- Nama Baju -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nama Baju</label>
                    <input type="text" id="editNamaBaju" name="nama_baju"
                        class="w-full border border-gray-300 rounded-md px-4 py-2" required>
                </div>

                <!-- Hari -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Hari</label>
                    <select id="editHari" name="hari" class="w-full border border-gray-300 rounded-md px-4 py-2"
                        required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="3"
                        class="w-full border border-gray-300 rounded-md px-4 py-2"></textarea>
                </div>

                <!-- Gambar Lama -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Gambar Lama</label>
                    <img id="previewEdit" class="h-24 mb-2 rounded hidden" alt="Preview Gambar Lama">
                </div>

                <!-- Upload Gambar Baru -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Ganti Gambar</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full border border-gray-300 rounded-md px-4 py-2">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModalEdit()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-sky-500 text-white font-semibold rounded hover:bg-sky-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalForm').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalForm').classList.add('hidden');
}

function searchTable() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function openModalEdit(id, nama, hari, deskripsi, gambar) {
    document.getElementById('editId').value = id;
    document.getElementById('editNamaBaju').value = nama;
    document.getElementById('editHari').value = hari;
    document.getElementById('editDeskripsi').value = deskripsi;

    let preview = document.getElementById('previewEdit');
    if (gambar && gambar !== "null") {
        preview.src = "{{ asset('assets/img') }}/" + gambar;
        preview.classList.remove('hidden');
    } else {
        preview.src = "";
        preview.classList.add('hidden');
    }

    // arahkan ke route update
    let form = document.getElementById('formEdit');
    form.action = "{{ url('bajus') }}/" + id + "/update";

    document.getElementById('modalEdit').classList.remove('hidden');
}


function closeModalEdit() {
    document.getElementById('modalEdit').classList.add('hidden');
}
</script>
@endsection