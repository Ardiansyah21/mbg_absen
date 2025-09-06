<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AbsensiKaryawan;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;



class AdminController extends Controller
{
    /**
     * Dashboard utama admin
     */
  public function index(Request $request)
    {
        // Tanggal hari ini
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        // Daftar tugas tetap dan warna card
        $tugasList = [
            'Persiapan'   => 'blue',
            'Memasak'     => 'red',
            'Packing'     => 'green',
            'Distribusi'  => 'yellow',
            'Kebersihan'  => 'purple',
            'Pencucian'   => 'indigo',
        ];

        // Ambil jumlah karyawan per tugas
        $jumlahPerTugas = [];
        foreach ($tugasList as $tugas => $warna) {
            $jumlahPerTugas[] = [
                'tugas' => $tugas,
                'warna' => $warna,
                'jumlah' => Karyawan::where('tugas', $tugas)->count()
            ];
        }

        // Ringkasan absensi hari ini
        $totalKaryawan = Karyawan::count();
        $jumlahHadir = Karyawan::whereHas('absensis', function($q) use($tanggal) {
            $q->whereDate('tanggal', $tanggal)
              ->where('status', 'Hadir');
        })->count();
        $jumlahTidakHadir = $totalKaryawan - $jumlahHadir;

        return view('admin.dashboard', compact(
            'jumlahPerTugas',
            'totalKaryawan',
            'jumlahHadir',
            'jumlahTidakHadir'
        ));
    }

    /**
     * Halaman daftar absensi
     */
    public function absen(Request $request)
    {
        // Ambil tanggal dari request (default hari ini)
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        $bulan = Carbon::parse($tanggal)->month;
        $tahun = Carbon::parse($tanggal)->year;

        // Ambil semua karyawan beserta absensi
        $karyawanList = Karyawan::with('absensis')->orderBy('nama')->get();

        // Pisahkan karyawan yang sudah dan belum absen hari ini
        $sudahAbsen = $karyawanList->filter(function($k) use($tanggal) {
            return $k->absensis->where('tanggal', $tanggal)->isNotEmpty();
        });

        $belumAbsen = $karyawanList->filter(function($k) use($tanggal) {
            return $k->absensis->where('tanggal', $tanggal)->isEmpty();
        });

        // Buat rekap bulanan
        $rekapAbsensi = $karyawanList->map(function($karyawan) use($bulan, $tahun) {

            $totalHari = Carbon::parse("$tahun-$bulan-01")->daysInMonth;

            // Ambil absensi bulan ini
            $absensiBulanIni = $karyawan->absensis->filter(function($a) use($bulan, $tahun) {
                return Carbon::parse($a->tanggal)->month == $bulan
                       && Carbon::parse($a->tanggal)->year == $tahun;
            });

            // Hitung jumlah hadir, case-insensitive, trim spasi
            $jumlahHadir = $absensiBulanIni->filter(function($a){
                return Str::lower(trim($a->status)) === 'hadir';
            })->count();

            $jumlahTidakHadir = $totalHari - $jumlahHadir;

            return (object)[
                'nama' => $karyawan->nama,
                'jumlah_hadir' => $jumlahHadir,
                'jumlah_tidak_hadir' => $jumlahTidakHadir,
                'total_hari' => $totalHari,
            ];
        });

        // Kirim semua data ke view
        return view('admin.absendata', [
            'tanggal'      => $tanggal,
            'karyawanList' => $karyawanList,
            'sudahAbsen'   => $sudahAbsen,
            'belumAbsen'   => $belumAbsen,
            'rekapAbsensi' => $rekapAbsensi,
        ]);
    }


    
public function exportPdf(Request $request)
{
    // Ambil tanggal dari request atau default hari ini
    $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

    $bulan = Carbon::parse($tanggal)->month;
    $tahun = Carbon::parse($tanggal)->year;

    // Ambil semua karyawan beserta absensi
    $karyawanList = Karyawan::with('absensis')->orderBy('nama')->get();

    // Buat rekap bulanan
    $rekapAbsensi = $karyawanList->map(function($karyawan) use($bulan, $tahun) {

        $totalHari = Carbon::parse("$tahun-$bulan-01")->daysInMonth;

        // Ambil absensi bulan ini
        $absensiBulanIni = $karyawan->absensis->filter(function($a) use($bulan, $tahun) {
            return Carbon::parse($a->tanggal)->month == $bulan
                   && Carbon::parse($a->tanggal)->year == $tahun;
        });

        // Hitung jumlah hadir
        $jumlahHadir = $absensiBulanIni->filter(function($a){
            return strtolower(trim($a->status)) === 'hadir';
        })->count();

        $jumlahTidakHadir = $totalHari - $jumlahHadir;

        return (object)[
            'nama' => $karyawan->nama,
            'jumlah_hadir' => $jumlahHadir,
            'jumlah_tidak_hadir' => $jumlahTidakHadir,
            'total_hari' => $totalHari,
        ];
    });

    // Generate PDF A4 landscape
    $pdf = Pdf::loadView('admin.pdf.rekap', [
        'rekapAbsensi' => $rekapAbsensi,
        'tanggal' => $tanggal
    ])->setPaper('a4', 'landscape')->setOptions([
        'margin-top' => 10,
        'margin-bottom' => 10,
        'margin-left' => 10,
        'margin-right' => 10,
    ]);

    return $pdf->stream('rekap-absensi-' . Carbon::parse($tanggal)->format('F-Y') . '.pdf');
}

public function exportPDFPerHari(Request $request)
{
    $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

    // Ambil semua karyawan beserta absensi hari ini
    $karyawanList = Karyawan::with(['absensis' => function($q) use($tanggal) {
        $q->whereDate('tanggal', $tanggal);
    }])->orderBy('nama')->get();

    $rekapHarian = $karyawanList->map(function($karyawan) {
        $absensi = $karyawan->absensis->first();
        
        // Gunakan base64 langsung dari database
        $ttdBase64 = $absensi && $absensi->tanda_tangan ? $absensi->tanda_tangan : null;

        return (object)[
            'nama' => $karyawan->nama,
            'status' => $absensi ? $absensi->status : 'Belum Absen',
            'waktu_masuk' => $absensi ? $absensi->waktu_masuk : '-',
            'waktu_keluar' => $absensi ? $absensi->waktu_keluar : '-',
            'tanda_tangan' => $ttdBase64,
        ];
    });

    // Load view PDF
    $pdf = PDF::loadView('admin.absen-harian-pdf', compact('rekapHarian', 'tanggal'))
              ->setPaper('a4', 'portrait')
              ->setOptions([
                  'margin-top' => 10,
                  'margin-bottom' => 10,
                  'margin-left' => 10,
                  'margin-right' => 10,
              ]);

    // Download PDF
    return $pdf->download("rekap_absen_" . Carbon::parse($tanggal)->format('Y-m-d') . ".pdf");
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}