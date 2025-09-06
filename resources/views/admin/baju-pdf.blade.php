<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Jadwal Baju</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        color: #333;
    }

    h2 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 10px;
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
        /* sky-500 */
        color: white;
    }

    td img {
        max-width: 80px;
        max-height: 60px;
    }
    </style>
</head>

<body>

    <h2>Jadwal Baju Harian SPPG</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Baju</th>
                <th>Hari</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapBaju as $index => $b)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $b->nama_baju }}</td>
                <td>{{ $b->hari }}</td>
                <td>{{ $b->deskripsi }}</td>
                <td>
                    @if($b->gambar)
                    <img src="{{ $b->gambar }}" alt="Gambar Baju">
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>