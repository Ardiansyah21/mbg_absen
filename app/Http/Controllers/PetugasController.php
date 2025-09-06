<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;



class PetugasController extends Controller
{
    // Tampilkan semua petugas dan data karyawan
public function index()
{
    // Ambil semua petugas beserta nama karyawan
    $petugas = Petugas::with('karyawan')->get();

    // Kelompokkan petugas berdasarkan tugas
    $petugasByTugas = $petugas->groupBy('tugas');

    // Ambil semua karyawan untuk dropdown
    $karyawans = Karyawan::all();

    return view('admin.petugas', compact('petugasByTugas', 'karyawans'));
}


    // Simpan jadwal petugas baru
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tugas' => 'required|string',
            'jam_masuk' => 'required',
        ]);

        // Ambil data karyawan
        $karyawan = Karyawan::findOrFail($request->karyawan_id);

        // Update tugas karyawan jika berubah
        $karyawan->tugas = $request->tugas;
        $karyawan->save();

        // Simpan ke tabel petugas
        Petugas::create([
            'karyawan_id' => $karyawan->id,
            'tugas' => $request->tugas,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => null, // Jam pulang belum diisi
        ]);

        return redirect()->back()->with('success', 'Jadwal petugas berhasil ditambahkan');
    }

    // Update tugas petugas
  public function update(Request $request, $id)
{
    $request->validate([
        'karyawan_id' => 'required|exists:karyawans,id',
        'tugas' => 'required|string',
        'jam_masuk' => 'required',
    ]);

    $petugas = Petugas::findOrFail($id);

    // Update data petugas
    $petugas->karyawan_id = $request->karyawan_id;
    $petugas->tugas = $request->tugas;
    $petugas->jam_masuk = $request->jam_masuk;
    $petugas->save();

    // Update tugas di tabel karyawan
    $karyawan = Karyawan::find($request->karyawan_id);
    if ($karyawan) {
        $karyawan->tugas = $request->tugas;
        $karyawan->save();
    }

    return redirect()->back()->with('success', 'Jadwal petugas berhasil diperbarui.');
}
public function exportPDF()
{
    // Ambil semua petugas dengan karyawan
    $petugas = Petugas::with('karyawan')->get();

    // Kelompokkan berdasarkan tugas
    $tugasList = ['Persiapan','Memasak','Packing','Distribusi','Kebersihan','Pencucian'];
    $petugasByTugas = $petugas->groupBy('tugas');

    $tanggal = Carbon::today()->toDateString();

    $pdf = PDF::loadView('admin.petugas-pdf', compact('petugasByTugas', 'tugasList', 'tanggal'))
              ->setPaper('a4', 'portrait')
              ->setOptions([
                  'margin-top' => 10,
                  'margin-bottom' => 10,
                  'margin-left' => 10,
                  'margin-right' => 10,
              ]);

    return $pdf->download("jadwal_petugas_" . Carbon::parse($tanggal)->format('Y-m-d') . ".pdf");
}
    // Hapus petugas
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->route('admin.petugas')->with('success', 'Petugas berhasil dihapus.');
    }
}