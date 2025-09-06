<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Peraturan</title>
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
        text-align: left;
        vertical-align: top;
    }

    th {
        background-color: #38bdf8;
        color: white;
    }

    ul {
        margin: 0;
        padding-left: 18px;
    }
    </style>
</head>

<body>

    <h2>Daftar Peraturan</h2>
    <p class="desc">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tugas</th>
                <th>Peraturan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peraturans as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->tugas }}</td>
                <td>
                    <ul>
                        @foreach(explode("\n", $p->deskripsi) as $poin)
                        <li>{{ $poin }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
            @if(count($peraturans) == 0)
            <tr>
                <td colspan="3" style="text-align:center">Belum ada data</td>
            </tr>
            @endif
        </tbody>
    </table>

</body>

</html>