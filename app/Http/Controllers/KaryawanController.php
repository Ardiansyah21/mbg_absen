<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use PDF; // DOMPDF

class KaryawanController extends Controller
{
    /**
     * Daftar semua karyawan.
     */
    public function index()
    {
        $karyawans = Karyawan::orderBy('id', 'desc')->get();
        return view('admin.karyawan', compact('karyawans'));
    }

    /**
     * Simpan karyawan baru.
     */
    public function store(Request $request)
    {
    $request->validate([
        'nama'  => 'required|string|max:100',
        'tugas' => 'required|in:Persiapan,Pengolahan,Pemorsian,Distribusi,Kebersihan,Pencucian,Asisten Lapangan,Koordinator Persiapan,Koordinator Pengolahan,Koordinator Pemorsian,Koordinator Distribusi,Koordinator Kebersihan,Koordinator Pencucian,PJ Persiapan,PJ Pengolahan,PJ Pemorsian,PJ Distribusi,PJ Kebersihan,PJ Pencucian',
    ]);

    Karyawan::create([
        'nama'  => $request->nama,
        'tugas' => $request->tugas,
    ]);

    return redirect()->back()->with('success', '✅ Karyawan berhasil ditambahkan!');
}


    /**
     * Update data karyawan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
        'tugas' => 'required|in:Persiapan,Pengolahan,Pemorsian,Distribusi,Kebersihan,Pencucian,Asisten Lapangan,Koordinator Persiapan,Koordinator Pengolahan,Koordinator Pemorsian,Koordinator Distribusi,Koordinator Kebersihan,Koordinator Pencucian,PJ Persiapan,PJ Pengolahan,PJ Pemorsian,PJ Distribusi,PJ Kebersihan,PJ Pencucian',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update([
            'nama'  => $request->nama,
            'tugas' => $request->tugas,
        ]);

        return redirect()->back()->with('success', '✅ Data karyawan berhasil diperbarui!');
    }

    /**
     * Hapus data karyawan.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->back()->with('success', '🗑️ Data karyawan berhasil dihapus!');
    }

    /**
     * Export data karyawan ke PDF.
     */
    public function exportPDF()
    {
        $karyawans = Karyawan::orderBy('tugas')->get();

        // Buat PDF A4 portrait
        $pdf = PDF::loadView('admin.karyawan-pdf', compact('karyawans'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('data_karyawan.pdf');
    }
}