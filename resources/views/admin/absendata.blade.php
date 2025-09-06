@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

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
    <!-- Tabel Absensi -->
    <div class="overflow-x-auto rounded-2xl shadow-md shadow-sky-200/70">
        <table class="min-w-full text-sm text-left text-gray-700" id="absensiTable">
            <thead class="bg-sky-500 text-white">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Tugas</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Hari</th>
                    <th class="px-6 py-3">Jam Masuk</th>
                    <th class="px-6 py-3">Jam Keluar</th>
                    <th class="px-6 py-3">Total Jam Kerja</th>
                    <th class="px-6 py-3">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php $no = 1; @endphp
                @foreach($sudahAbsen as $karyawan)
                @foreach($karyawan->absensis as $absensi)
                <tr class="hover:bg-sky-50 transition">
                    <td class="px-6 py-4">{{ $no++ }}</td>
                    <td class="px-6 py-4 font-medium">{{ $karyawan->nama }}</td>
                    <td class="px-6 py-4">{{ $karyawan->tugas ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $absensi->status }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}</td>
                    <td class="px-6 py-4">{{ $absensi->hari }}</td>
                    <td class="px-6 py-4">{{ $absensi->waktu_masuk }}</td>
                    <td class="px-6 py-4">{{ $absensi->waktu_keluar ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($absensi->waktu_masuk && $absensi->waktu_keluar)
                        @php
                        $masuk = \Carbon\Carbon::parse($absensi->waktu_masuk);
                        $keluar = \Carbon\Carbon::parse($absensi->waktu_keluar);
                        $diff = $keluar->diff($masuk);

                        $jamKerja = $diff->h + ($diff->d * 24); // hitung jam termasuk jika beda hari
                        $menitKerja = $diff->i;
                        $detikKerja = $diff->s;
                        @endphp
                        {{ $jamKerja }} jam {{ $menitKerja }} menit {{ $detikKerja }} detik
                        @else
                        -
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        @if($absensi->tanda_tangan)
                        <img src="{{ $absensi->tanda_tangan }}" alt="Tanda Tangan"
                            class="w-16 h-12 object-contain rounded shadow">
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
                @endforeach

                @if($sudahAbsen->isEmpty())
                <tr>
                    <td colspan="10" class="text-center py-4">Belum ada yang absen di tanggal ini.</td>
                </tr>
                @endif
            </tbody>
        </table>
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




</div>
<!-- Script Filter & Search -->
<script>
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
    const input = document.getElementById("searchInput").value.toLowerCase();
    const table = document.getElementById("absensiTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let match = false;
        for (let j = 1; j < 3; j++) { // Nama & Tugas
            if (cells[j].textContent.toLowerCase().includes(input)) {
                match = true;
                break;
            }
        }
        rows[i].style.display = match ? "" : "none";
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