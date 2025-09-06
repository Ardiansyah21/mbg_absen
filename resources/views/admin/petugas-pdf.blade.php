<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Jadwal Petugas</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        color: #333;
    }

    h2 {
        text-align: center;
        color: #38bdf8;
        margin-bottom: 5px;
    }

    p.desc {
        text-align: center;
        font-size: 10px;
        margin-top: 0;
        margin-bottom: 15px;
    }

    h3 {
        color: #38bdf8;
        margin-bottom: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    th,
    td {
        border: 1px solid #333;
        padding: 6px;
        text-align: center;
        font-size: 12px;
    }

    th {
        background-color: #38bdf8;
        color: white;
    }

    .footer {
        margin-top: 20px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
        font-size: 10px;
    }

    .footer div {
        text-align: center;
        width: 150px;
    }
    </style>
</head>

<body>

    <h2>Daftar Jadwal Petugas</h2>
    <p class="desc">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>

    @foreach($tugasList as $tugas)
    <h3>Tugas {{ $tugas }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Tugas</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($petugasByTugas[$tugas]))
            @foreach($petugasByTugas[$tugas] as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->karyawan->nama ?? 'Tidak ada' }}</td>
                <td>{{ $p->jam_masuk }}</td>
                <td>{{ $p->jam_pulang ?? 'Selesai' }}</td>
                <td>{{ $p->tugas }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5">Belum ada data</td>
            </tr>
            @endif
        </tbody>
    </table>
    @endforeach

</body>

</html>