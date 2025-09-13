@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    @if(session('success'))
    <div class="mb-4 p-4 rounded bg-green-100 border border-green-400 text-green-700 flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
    </div>
    @endif

    <!-- Header dengan Search + Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-blue-500">Daftar Pengguna</h1>

        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full md:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                </span>
                <input type="text" id="searchInput" onkeyup="searchTable()"
                    class="w-full pl-10 pr-4 py-2 rounded-full shadow-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition"
                    placeholder="Cari nama atau tugas...">
            </div>

            <!-- Buttons -->
            <button onclick="openModal()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah Pengguna
            </button>
            <a href="{{ route('karyawan.pdf') }}" target="_blank"
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
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Tugas</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($karyawans as $karyawan)
                    <tr class="hover:bg-sky-50 transition">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium">{{ $karyawan->nama }}</td>
                        <td class="px-6 py-4">{{ $karyawan->tugas }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                                onclick="editData({id:{{ $karyawan->id }}, nama:'{{ $karyawan->nama }}', tugas:'{{ $karyawan->tugas }}'})">
                                Edit
                            </button>

                            <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST"
                                onsubmit="return confirmDelete(event, '{{ addslashes($karyawan->nama) }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Hapus
                                </button>
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
            <h2 class="text-xl font-bold text-sky-600">Tambah Karyawan</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form action="{{ route('karyawan.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nama</label>
                    <input type="text" name="nama" class="w-full border border-gray-300 rounded-md px-4 py-2"
                        placeholder="Masukkan nama" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Tugas</label>
                    <select name="tugas" class="w-full border border-gray-300 rounded-md px-4 py-2" required>
                        <option value="">-- Pilih Tugas --</option>

                        <!-- Tugas utama -->
                        <option value="Persiapan">Persiapan</option>
                        <option value="Pengolahan">Pengolahan</option>
                        <option value="Pemorsian">Pemorsian</option>
                        <option value="Distribusi">Distribusi</option>
                        <option value="Kebersihan">Kebersihan</option>
                        <option value="Pencucian">Pencucian</option>
                        <option value="Asisten Lapangan">Asisten Lapangan</option>

                        <!-- Koordinator -->
                        <option value="Koordinator Persiapan">Koordinator Persiapan</option>
                        <option value="Koordinator Pengolahan">Koordinator Pengolahan</option>
                        <option value="Koordinator Pemorsian">Koordinator Pemorsian</option>
                        <option value="Koordinator Distribusi">Koordinator Distribusi</option>
                        <option value="Koordinator Kebersihan">Koordinator Kebersihan</option>
                        <option value="Koordinator Pencucian">Koordinator Pencucian</option>

                        <!-- PJ -->
                        <option value="PJ Persiapan">PJ Persiapan</option>
                        <option value="PJ Pengolahan">PJ Pengolahan</option>
                        <option value="PJ Pemorsian">PJ Pemorsian</option>
                        <option value="PJ Distribusi">PJ Distribusi</option>
                        <option value="PJ Kebersihan">PJ Kebersihan</option>
                        <option value="PJ Pencucian">PJ Pencucian</option>
                    </select>
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
            <h2 class="text-xl font-bold text-sky-600">Edit Karyawan</h2>
            <button onclick="closeModalEdit()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>

        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId" name="id">

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nama</label>
                    <input type="text" id="editName" name="nama"
                        class="w-full border border-gray-300 rounded-md px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Tugas</label>
                    <select id="editTugas" name="tugas" class="w-full border border-gray-300 rounded-md px-4 py-2"
                        required>
                        <option value="">-- Pilih Tugas --</option>

                        <!-- Tugas utama -->
                        <option value="Persiapan">Persiapan</option>
                        <option value="Pengolahan">Pengolahan</option>
                        <option value="Pemorsian">Pemorsian</option>
                        <option value="Distribusi">Distribusi</option>
                        <option value="Kebersihan">Kebersihan</option>
                        <option value="Pencucian">Pencucian</option>
                        <option value="Asisten Lapangan">Asisten Lapangan</option>

                        <!-- Koordinator -->
                        <option value="Koordinator Persiapan">Koordinator Persiapan</option>
                        <option value="Koordinator Pengolahan">Koordinator Pengolahan</option>
                        <option value="Koordinator Pemorsian">Koordinator Pemorsian</option>
                        <option value="Koordinator Distribusi">Koordinator Distribusi</option>
                        <option value="Koordinator Kebersihan">Koordinator Kebersihan</option>
                        <option value="Koordinator Pencucian">Koordinator Pencucian</option>

                        <!-- PJ -->
                        <option value="PJ Persiapan">PJ Persiapan</option>
                        <option value="PJ Pengolahan">PJ Pengolahan</option>
                        <option value="PJ Pemorsian">PJ Pemorsian</option>
                        <option value="PJ Distribusi">PJ Distribusi</option>
                        <option value="PJ Kebersihan">PJ Kebersihan</option>
                        <option value="PJ Pencucian">PJ Pencucian</option>
                    </select>
                </div>
            </div>

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

function editData(data) {
    const form = document.getElementById('formEdit');
    form.action = `/karyawan/${data.id}`;

    document.getElementById('editId').value = data.id;
    document.getElementById('editName').value = data.nama;
    document.getElementById('editTugas').value = data.tugas;

    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeModalEdit() {
    document.getElementById('modalEdit').classList.add('hidden');
}

function confirmDelete(event, nama) {
    event.preventDefault(); // hentikan submit sementara
    if (confirm(`Yakin ingin menghapus karyawan "${nama}"? Data absensi akan tetap ada.`)) {
        event.target.submit(); // lanjutkan submit
    }
    return false;
}
</script>
@endsection