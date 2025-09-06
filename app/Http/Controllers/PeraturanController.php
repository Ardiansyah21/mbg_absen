<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class PeraturanController extends Controller
{
    public function index()
    {
        $peraturans = Peraturan::all();
        return view('admin.peraturan', compact('peraturans'));
    }

    public function store(Request $request)
    {
        $count = Peraturan::count();

        if($count >= 6) {
            return redirect()->back()->with('error', 'Data peraturan sudah lengkap. Silahkan edit data yang ada.');
        }

        $request->validate([
            'tugas' => 'required|unique:peraturan,tugas',
            'deskripsi' => 'required|string',
        ]);

        Peraturan::create([
            'tugas' => $request->tugas,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $peraturan = Peraturan::findOrFail($id);

        $request->validate([
            'tugas' => 'required|unique:peraturan,tugas,'.$id,
            'deskripsi' => 'required|string',
        ]);

        $peraturan->update([
            'tugas' => $request->tugas,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with('success', 'Peraturan berhasil diupdate.');
    }

   public function exportPDF()
{
    $peraturans = Peraturan::all();
    $tanggal = Carbon::today()->toDateString();

    $pdf = PDF::loadView('admin.peraturan-pdf', compact('peraturans', 'tanggal'))
              ->setPaper('a4', 'portrait');

    return $pdf->download("peraturan_" . Carbon::parse($tanggal)->format('Y-m-d') . ".pdf");
}

    public function destroy($id)
    {
        Peraturan::destroy($id);
        return redirect()->back()->with('success', 'Peraturan berhasil dihapus.');
    }
}