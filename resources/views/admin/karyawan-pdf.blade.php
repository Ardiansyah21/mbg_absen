<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Relawan SPPG</title>
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #333;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header img {
        width: 80px;
        height: auto;
        margin-bottom: 10px;
    }

    .header h1 {
        font-size: 20px;
        color: #0d6efd;
        margin: 0;
    }

    .header p {
        margin: 2px 0;
        font-size: 12px;
        color: #38bdf8;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        border: 1px solid #dee2e6;
        padding: 8px;
    }

    th {
        background-color: #38bdf8;
        color: white;
        text-align: center;
    }

    td {
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .footer {
        margin-top: 30px;
        font-size: 10px;
        text-align: right;
        color: #666;
    }
    </style>
</head>

<body>
    <div class="header" style="text-align:center; margin-bottom:20px;">
        <img src="{{ public_path('assets/img/logo-bgn.png') }}" alt="Logo SPPG"
            style="width:80px; height:auto; margin-bottom:10px;">
        <h1 style="font-size:20px; color:#38bdf8; margin:0;">Daftar Relawan SPPG</h1>
        <p style="margin:2px 0; font-size:12px; color:#555;">Data relawan aktif SPPG beserta tugas masing-masing</p>
        <p style="margin:2px 0; font-size:12px; color:#555;">Periode:
            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawans as $index => $k)
            <tr>
                <td style="text-align:center">{{ $index + 1 }}</td>
                <td>{{ $k->nama }}</td>
                <td>{{ $k->tugas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
    </div>
</body>

</html>