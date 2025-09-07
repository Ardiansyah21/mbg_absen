<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Harian</title>
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #333;
        padding: 6px;
        text-align: center;
    }

    th {
        background-color: #38bdf8;
        color: white;
    }

    td img {
        max-width: 60px;
        max-height: 40px;
    }

    .footer {
        margin-top: 30px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
        font-size: 10px;
    }

    .footer .mengetahui {
        text-align: center;
        width: 200px;
    }
    </style>
</head>

<body>

    <h2>Rekap Absensi Harian</h2>
    <p class="desc">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tugas</th>
                <th>Status</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Tanda Tangan Karyawan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapHarian as $index => $k)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->tugas ?? '-' }}</td>
                <td>{{ $k->status }}</td>
                <td>{{ $k->waktu_masuk }}</td>
                <td>{{ $k->waktu_keluar }}</td>
                <td>
                    @if($k->tanda_tangan)
                    <img src="{{ $k->tanda_tangan }}" alt="Tanda Tangan" style="width:100px; height:auto;">
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach

            @if($rekapHarian->isEmpty())
            <tr>
                <td colspan="7">Belum ada data absensi hari ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <div class="mengetahui">
            Mengetahui<br><br><br><br>
            (................)
        </div>
    </div>

</body>

</html>