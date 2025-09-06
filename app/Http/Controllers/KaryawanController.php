<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use PDF; // import DOMPDF

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = Karyawan::all(); // ambil semua data karyawan
        return view('admin.karyawan', compact('karyawans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tugas' => 'required|in:Persiapan,Memasak,Packing,Distribusi,Kebersihan,Pencucian',
        ]);

        Karyawan::create([
            'nama' => $request->nama,
            'tugas' => $request->tugas,
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tugas' => 'required|in:Persiapan,Memasak,Packing,Distribusi,Kebersihan,Pencucian',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->only('nama', 'tugas'));

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus!');
    }

    /**
     * Export semua karyawan ke PDF
     */
    public function exportPDF()
{
    $karyawans = Karyawan::all();

    // Generate PDF ukuran A4 portrait
    $pdf = PDF::loadView('admin.karyawan-pdf', compact('karyawans'))
              ->setPaper('a4', 'portrait'); // ganti 'landscape' kalau tabel lebar

    return $pdf->download('data_karyawan.pdf');
}

}