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
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        // Daftar tugas / jabatan lengkap
        $tugasList = [
            'Persiapan',
            'Memasak',
            'Packing',
            'Distribusi',
            'Kebersihan',
            'Pencucian',
            'Asisten Lapangan',
            'Koordinator Persiapan',
            'Koordinator Memasak',
            'Koordinator Packing',
            'Koordinator Distribusi',
            'Koordinator Kebersihan',
            'Koordinator Pencucian',
            'Koordinator Asisten Lapangan',
        ];

        // Mapping warna unik Tailwind
        $warnaClasses = [
            'Persiapan' => 'blue',
            'Memasak' => 'red',
            'Packing' => 'green',
            'Distribusi' => 'yellow',
            'Kebersihan' => 'purple',
            'Pencucian' => 'indigo',
            'Asisten Lapangan' => 'blue',
            'Koordinator Persiapan' => 'green',
            'Koordinator Memasak' => 'yellow',
            'Koordinator Packing' => 'purple',
            'Koordinator Distribusi' => 'indigo',
            'Koordinator Kebersihan' => 'blue',
            'Koordinator Pencucian' => 'red',
            'Koordinator Asisten Lapangan' => 'green',
        ];

        // Hitung jumlah karyawan per tugas
        $jumlahPerTugas = [];
        foreach ($tugasList as $tugas) {
            $jumlahPerTugas[] = [
                'tugas'  => $tugas,
                'warna'  => $warnaClasses[$tugas] ?? 'gray',
                'jumlah' => Karyawan::where('tugas', $tugas)->count(),
            ];
        }

        $totalKaryawan = Karyawan::count();
        $jumlahHadir = Karyawan::whereHas('absensis', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal)->where('status', 'Hadir');
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
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        $bulan = Carbon::parse($tanggal)->month;
        $tahun = Carbon::parse($tanggal)->year;

        $karyawanList = Karyawan::with('absensis')->orderBy('nama')->get();

        $sudahAbsen = $karyawanList->filter(function ($k) use ($tanggal) {
            return $k->absensis->where('tanggal', $tanggal)->isNotEmpty();
        });

        $belumAbsen = $karyawanList->filter(function ($k) use ($tanggal) {
            return $k->absensis->where('tanggal', $tanggal)->isEmpty();
        });

        $rekapAbsensi = $karyawanList->map(function ($karyawan) use ($bulan, $tahun) {
            $totalHari = Carbon::parse("$tahun-$bulan-01")->daysInMonth;

            $absensiBulanIni = $karyawan->absensis->filter(function ($a) use ($bulan, $tahun) {
                return Carbon::parse($a->tanggal)->month == $bulan &&
                       Carbon::parse($a->tanggal)->year == $tahun;
            });

            $jumlahHadir = $absensiBulanIni->filter(function ($a) {
                return Str::lower(trim($a->status)) === 'hadir';
            })->count();

            $jumlahTidakHadir = $totalHari - $jumlahHadir;

            return (object) [
                'nama'              => $karyawan->nama,
                'tugas'             => $karyawan->tugas,
                'jumlah_hadir'      => $jumlahHadir,
                'jumlah_tidak_hadir'=> $jumlahTidakHadir,
                'total_hari'        => $totalHari,
            ];
        });

        return view('admin.absendata', compact(
            'tanggal',
            'karyawanList',
            'sudahAbsen',
            'belumAbsen',
            'rekapAbsensi'
        ));
    }

    /**
     * Export PDF Rekap Bulanan
     */
    public function exportPdf(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        $bulan = Carbon::parse($tanggal)->month;
        $tahun = Carbon::parse($tanggal)->year;

        $karyawanList = Karyawan::with('absensis')->orderBy('nama')->get();

        $rekapAbsensi = $karyawanList->map(function ($karyawan) use ($bulan, $tahun) {
            $totalHari = Carbon::parse("$tahun-$bulan-01")->daysInMonth;

            $absensiBulanIni = $karyawan->absensis->filter(function ($a) use ($bulan, $tahun) {
                return Carbon::parse($a->tanggal)->month == $bulan &&
                       Carbon::parse($a->tanggal)->year == $tahun;
            });

            $jumlahHadir = $absensiBulanIni->filter(function ($a) {
                return strtolower(trim($a->status)) === 'hadir';
            })->count();

            $jumlahTidakHadir = $totalHari - $jumlahHadir;

            return (object) [
                'nama'              => $karyawan->nama,
                'tugas'             => $karyawan->tugas,
                'jumlah_hadir'      => $jumlahHadir,
                'jumlah_tidak_hadir'=> $jumlahTidakHadir,
                'total_hari'        => $totalHari,
            ];
        });

        $pdf = Pdf::loadView('admin.pdf.rekap', [
            'rekapAbsensi' => $rekapAbsensi,
            'tanggal'      => $tanggal,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('rekap-absensi-' . Carbon::parse($tanggal)->format('F-Y') . '.pdf');
    }

    /**
     * Export PDF Rekap Per Hari
     */
    public function exportPDFPerHari(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        $karyawanList = Karyawan::with(['absensis' => function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        }])->orderBy('nama')->get();

        $rekapHarian = $karyawanList->map(function ($karyawan) {
            $absensi = $karyawan->absensis->first();
            $ttdBase64 = $absensi && $absensi->tanda_tangan ? $absensi->tanda_tangan : null;

            return (object) [
                'nama'         => $karyawan->nama,
                'tugas'        => $karyawan->tugas,
                'status'       => $absensi ? $absensi->status : 'Belum Absen',
                'waktu_masuk'  => $absensi ? $absensi->waktu_masuk : '-',
                'waktu_keluar' => $absensi ? $absensi->waktu_keluar : '-',
                'tanda_tangan' => $ttdBase64,
            ];
        });

        $pdf = Pdf::loadView('admin.absen-harian-pdf', compact('rekapHarian', 'tanggal'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("rekap_absen_" . Carbon::parse($tanggal)->format('Y-m-d') . ".pdf");
    }
}