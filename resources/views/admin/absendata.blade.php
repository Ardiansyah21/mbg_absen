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

    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-blue-500">Daftar Absen -
            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h1>

        <form method="GET" action="{{ route('admin.absensi') }}"
            class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full md:w-auto">
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

            <!-- Filter Tanggal -->
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="px-4 py-2 rounded border border-gray-300 shadow-md focus:outline-none focus:ring-2 focus:ring-sky-400 transition">


            <button type="submit" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Filter
            </button>

            <a href="{{ route('admin.export-pdf-harian', ['tanggal' => $tanggal]) }}" target="_blank"
                class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded shadow">
                Export PDF
            </a>

        </form>
    </div>
    <div class="w-full overflow-x-auto rounded-2xl shadow-md shadow-sky-200/70">
        <div class="inline-block min-w-full">
            <table id="absensiTable" class="table-auto text-sm text-left text-gray-700 w-full">
                <thead class="bg-sky-500 text-white">
                    <tr>
                        <th class="px-3 py-2">No</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Tugas</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Hari & Tanggal</th>
                        <th class="px-3 py-2">Jam Masuk</th>
                        <th class="px-3 py-2">Jam Keluar</th>
                        <th class="px-3 py-2">Total Jam Kerja</th>
                        <th class="px-3 py-2">Tanda Tangan</th>
                        <th class="px-3 py-2">Pengganti & Keterangan</th>
                        <th class="px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody class=" divide-y divide-gray-200">
                    @php $no = 1; @endphp
                    @foreach($sudahAbsen as $karyawan)
                    @foreach($karyawan->absensis as $absensi)
                    <tr class="hover:bg-sky-50 transition">
                        <td class="px-3 py-2">{{ $no++ }}</td>
                        <td class="px-3 py-2 font-medium">{{ $karyawan->nama }}</td>
                        <td class="px-3 py-2">{{ $karyawan->tugas ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $absensi->status }}</td>
                        <td class="px-3 py-2">{{ $absensi->hari }},
                            {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}</td>
                        <td class="px-3 py-2">{{ $absensi->waktu_masuk }}</td>
                        <td class="px-3 py-2">{{ $absensi->waktu_keluar ?? '-' }}</td>
                        <td class="px-3 py-2">
                            @if($absensi->waktu_masuk && $absensi->waktu_keluar)
                            @php
                            $masuk = \Carbon\Carbon::parse($absensi->waktu_masuk);
                            $keluar = \Carbon\Carbon::parse($absensi->waktu_keluar);
                            $diff = $keluar->diff($masuk);
                            $jamKerja = $diff->h + ($diff->d * 24);
                            $menitKerja = $diff->i;
                            $detikKerja = $diff->s;
                            @endphp
                            {{ $jamKerja }} jam {{ $menitKerja }} menit {{ $detikKerja }} detik
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            @if($absensi->tanda_tangan)
                            <img src="{{ $absensi->tanda_tangan }}" alt="Tanda Tangan"
                                class="w-16 h-12 object-contain rounded shadow">
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            @if($absensi->nama_pengganti)
                            <div>
                                <p><span class="font-medium">Nama:</span> {{ $absensi->nama_pengganti }}</p>
                                <p><span class="font-medium">Ket:</span> Hadir menggantikan {{ $karyawan->nama }}</p>
                            </div>
                            @else
                            -
                            @endif
                        </td>

                        <td class="px-3 py-2 text-center sticky right-0 bg-white">
                            <div class="flex flex-col gap-2 items-center">
                                <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin mau hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-xs shadow w-24">Hapus</button>
                                </form>
                                <a href="javascript:void(0)" onclick="openDuplikatModal({{ $absensi->id }})"
                                    class="px-3 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-xs shadow w-24 text-center">
                                    Duplikat
                                </a>

                                <a href="javascript:void(0)" onclick="openModal({{ $absensi->id }})"
                                    class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 text-xs shadow w-24 text-center">Pengganti</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    @if($sudahAbsen->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center py-4">Belum ada yang absen di tanggal ini.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>






    <!-- Tabel Karyawan Belum Absen -->
    <h2 class="text-lg font-bold mt-8 mb-3 text-red-500">❌ Belum Absen -
        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h2>
    <div class="overflow-x-auto rounded-2xl shadow-md shadow-red-200/70 mb-6">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-red-500 text-white">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Tugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($belumAbsen as $index => $karyawan)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium">{{ $karyawan->nama }}</td>
                    <td class="px-6 py-4">{{ $karyawan->tugas ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-green-600 font-semibold">Semua karyawan sudah absen 🎉
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mb-8">
        <!-- Header & Export Button -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <h2 class="text-lg font-bold text-green-600 flex items-center gap-2">
                📊 Rekap Absensi Bulanan - {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('F Y') }}
            </h2>
            <a href="{{ route('admin.rekap.pdf', ['tanggal' => $tanggal]) }}" target="_blank"
                class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded shadow">
                Export PDF Rekap Perbulan
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl shadow-lg shadow-green-200/40">
            <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-center">No</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3 text-center">Jumlah Hadir</th>
                        <th class="px-6 py-3 text-center">Jumlah Tidak Hadir</th>
                        <th class="px-6 py-3 text-center">Total Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $no = 1; @endphp
                    @foreach($rekapAbsensi as $karyawan)
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-6 py-4 text-center font-medium">{{ $no++ }}</td>
                        <td class="px-6 py-4 font-medium">{{ $karyawan->nama }}</td>
                        <td class="px-6 py-4 text-center">{{ $karyawan->jumlah_hadir }}</td>
                        <td class="px-6 py-4 text-center">{{ $karyawan->jumlah_tidak_hadir }}</td>
                        <td class="px-6 py-4 text-center">{{ $karyawan->total_hari }}</td>
                    </tr>
                    @endforeach

                    @if($rekapAbsensi->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Belum ada data rekap bulanan.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal Pengganti -->
    <div id="modalPengganti" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl shadow-green-500 w-full max-w-md p-6 border-t-4 border-green-500">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-green-600">Form Pengganti</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <!-- Isi Form -->
            <form id="formPengganti" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Pengganti</label>
                    <input type="text" name="nama_pengganti"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Simpan</button>
                </div>
            </form>

        </div>
    </div>


    <!-- Modal Duplikat -->
    <div id="modalDuplikat" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl shadow-yellow-500 w-full max-w-md p-6 border-t-4 border-yellow-500">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-yellow-600">Duplikat Absensi</h2>
                <button onclick="closeDuplikatModal()"
                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form id="formDuplikat" method="POST" class="space-y-4">
                @csrf

                <!-- Tanggal baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Baru</label>
                    <input type="date" name="tanggal"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500"
                        required>
                </div>

                <!-- Jam Masuk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                    <input type="time" name="waktu_masuk"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Jam Keluar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                    <input type="time" name="waktu_keluar"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alfa">Alfa</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDuplikatModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Duplikat</button>
                </div>
            </form>
        </div>
    </div>





</div>
<!-- Script Filter & Search -->
<script>
function openDuplikatModal(absensiId) {
    let form = document.getElementById('formDuplikat');
    form.action = `/absensi/${absensiId}/duplikat`;
    document.getElementById('modalDuplikat').classList.remove('hidden');
}

function closeDuplikatModal() {
    document.getElementById('modalDuplikat').classList.add('hidden');
}

function openModal(absensiId) {
    let form = document.getElementById('formPengganti');
    form.action = "{{ url('/absensi') }}/" + absensiId + "/pengganti";
    document.getElementById('modalPengganti').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalPengganti').classList.add('hidden');
}
document.getElementById('lihatSemua').addEventListener('click', () => {
    fetch("/admin/absensi/all") // endpoint baru untuk semua data
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#absensiTable tbody');
            tbody.innerHTML = ''; // kosongkan dulu
            data.forEach((absensi, index) => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-sky-50', 'transition');
                row.innerHTML = `
                    <td class="px-6 py-4">${index+1}</td>
                    <td class="px-6 py-4 font-medium">${absensi.karyawan.nama}</td>
                    <td class="px-6 py-4">${absensi.karyawan.tugas}</td>
                    <td class="px-6 py-4">${absensi.status}</td>
                    <td class="px-6 py-4">${absensi.tanggal}</td>
                    <td class="px-6 py-4">${absensi.hari}</td>
                    <td class="px-6 py-4">${absensi.waktu_masuk}</td>
                    <td class="px-6 py-4">${absensi.waktu_keluar ?? '-'}</td>
                    <td class="px-6 py-4">${absensi.tanda_tangan ? `<img src="${absensi.tanda_tangan}" class="w-16 h-12 object-contain rounded shadow">` : '-'}</td>
                `;
                tbody.appendChild(row);
            });
        });
});


function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('absensiTable'); // pastikan ID tabel sesuai
    const trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) { // mulai dari 1 agar header tidak ikut
        const tds = trs[i].getElementsByTagName('td');
        let nama = tds[1]?.textContent.toLowerCase() || ''; // kolom Nama
        let tugas = tds[2]?.textContent.toLowerCase() || ''; // kolom Tugas

        if (nama.includes(filter) || tugas.includes(filter)) {
            trs[i].style.display = '';
        } else {
            trs[i].style.display = 'none';
        }
    }
}

document.getElementById('filterStatus').addEventListener('change', () => {
    const status = document.getElementById('filterStatus').value.toLowerCase();
    const rows = document.querySelectorAll('#absensiTable tbody tr');
    rows.forEach(row => {
        const rowStatus = row.cells[3].textContent.toLowerCase();
        row.style.display = (status === '' || rowStatus === status) ? '' : 'none';
    });
});

document.getElementById('filterDate').addEventListener('change', () => {
    const date = document.getElementById('filterDate').value;
    const rows = document.querySelectorAll('#absensiTable tbody tr');
    rows.forEach(row => {
        const rowDate = new Date(row.cells[4].textContent).toISOString().split('T')[0];
        row.style.display = (date === '' || rowDate === date) ? '' : 'none';
    });
});
</script>
@endsection