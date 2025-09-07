<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PetugasController extends Controller
{
    /**
     * Tampilkan semua petugas dan data karyawan.
     */
    public function index()
    {
        // Urutan tugas yang diinginkan
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

        // Ambil semua petugas dengan urutan sesuai FIELD()
        $petugas = Petugas::with('karyawan')
            ->orderByRaw("FIELD(tugas, '" . implode("','", $tugasList) . "')")
            ->orderBy('jam_masuk', 'asc')
            ->get();

        // Kelompokkan petugas berdasarkan tugas
        $petugasByTugas = $petugas->groupBy('tugas');

        // Ambil semua karyawan untuk dropdown
        $karyawans = Karyawan::orderBy('nama')->get();

        return view('admin.petugas', compact('petugasByTugas', 'karyawans', 'tugasList'));
    }

    /**
     * Simpan jadwal petugas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tugas'       => 'required|string',
            'jam_masuk'   => 'required|date_format:H:i',
        ]);

        $karyawan = Karyawan::findOrFail($request->karyawan_id);

        // Update tugas di tabel karyawan
        $karyawan->tugas = $request->tugas;
        $karyawan->save();

        // Simpan jadwal petugas
        Petugas::create([
            'karyawan_id' => $karyawan->id,
            'tugas'       => $request->tugas,
            'jam_masuk'   => $request->jam_masuk,
            'jam_pulang'  => null,
        ]);

        return redirect()->back()->with('success', '✅ Jadwal petugas berhasil ditambahkan!');
    }

    /**
     * Update jadwal petugas.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tugas'       => 'required|string',
            'jam_masuk'   => 'required|date_format:H:i',
        ]);

        $petugas = Petugas::findOrFail($id);

        // Update data petugas
        $petugas->update([
            'karyawan_id' => $request->karyawan_id,
            'tugas'       => $request->tugas,
            'jam_masuk'   => $request->jam_masuk,
        ]);

        // Update juga tugas karyawan
        $karyawan = Karyawan::find($request->karyawan_id);
        if ($karyawan) {
            $karyawan->tugas = $request->tugas;
            $karyawan->save();
        }

        return redirect()->back()->with('success', '✅ Jadwal petugas berhasil diperbarui!');
    }

    /**
     * Export jadwal petugas ke PDF.
     */
    public function exportPDF()
    {
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

        $petugas = Petugas::with('karyawan')
            ->orderByRaw("FIELD(tugas, '" . implode("','", $tugasList) . "')")
            ->orderBy('jam_masuk', 'asc')
            ->get();

        $petugasByTugas = $petugas->groupBy('tugas');
        $tanggal = Carbon::today()->format('Y-m-d');

        $pdf = Pdf::loadView('admin.petugas-pdf', compact('petugasByTugas', 'tugasList', 'tanggal'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("jadwal_petugas_{$tanggal}.pdf");
    }

    /**
     * Hapus jadwal petugas.
     */
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();

        return redirect()->back()->with('success', '🗑️ Jadwal petugas berhasil dihapus!');
    }
}