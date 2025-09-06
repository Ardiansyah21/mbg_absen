<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Baju;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;



class BajuController extends Controller
{
    public function index()
{
    $bajus = Baju::all();

    $urutanHari = ['Senin','Selasa','Rabu','Kamis','Jumat'];

    $bajus = $bajus->sortBy(function ($item) use ($urutanHari) {
        return array_search($item->hari, $urutanHari);
    });

    return view('admin.baju', compact('bajus'));
}


    public function store(Request $request)
{
    // validasi input
    $request->validate([
        'nama_baju' => 'required|string|max:255',
        'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat', // hanya 5 hari
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // cek jumlah data baju
    $total = Baju::count();
    if ($total >= 5) {
        return redirect()->back()->with('error', 'Data baju sudah lengkap dari Senin sampai Jumat. Silakan edit jika ada perubahan.');
    }

    // (opsional) cek apakah hari sudah ada
    if (Baju::where('hari', $request->hari)->exists()) {
        return redirect()->back()->with('error', "Jadwal untuk hari {$request->hari} sudah ada. Silakan edit data tersebut.");
    }

    // simpan data
    $data = $request->only(['nama_baju', 'hari', 'deskripsi']);

    if ($request->hasFile('gambar')) {
        $gambar = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('assets/img'), $gambar);
        $data['gambar'] = $gambar;
    }

    Baju::create($data);

    return redirect()->route('admin.baju')->with('success', 'Jadwal baju berhasil ditambahkan.');
}


  public function update(Request $request, $id)
{
    $baju = Baju::findOrFail($id);

    $request->validate([
        'nama_baju' => 'required|string|max:255',
        'hari' => 'required|string|max:50',
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $baju->nama_baju = $request->nama_baju;
    $baju->hari = $request->hari;
    $baju->deskripsi = $request->deskripsi;

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $namaFile = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('assets/img'), $namaFile);
        $baju->gambar = $namaFile;
    }

    $baju->save();

    return redirect()->back()->with('success', 'Jadwal baju berhasil diupdate!');
}



public function exportPDF()
{
    // Ambil semua data baju, urutkan sesuai hari Senin-Jumat
    $urutanHari = ['Senin','Selasa','Rabu','Kamis','Jumat'];
    $bajus = Baju::all()->sortBy(function ($item) use ($urutanHari) {
        return array_search($item->hari, $urutanHari);
    });

    // Ubah gambar ke base64 supaya bisa tampil di PDF
    $rekapBaju = $bajus->map(function($baju) {
        $gambarBase64 = null;
        if($baju->gambar && file_exists(public_path('assets/img/' . $baju->gambar))) {
            $gambarBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/img/' . $baju->gambar)));
        }

        return (object)[
            'nama_baju' => $baju->nama_baju,
            'hari' => $baju->hari,
            'deskripsi' => $baju->deskripsi ?? '-',
            'gambar' => $gambarBase64,
        ];
    });

    $pdf = PDF::loadView('admin.baju-pdf', compact('rekapBaju'))
              ->setPaper('a4', 'portrait')
              ->setOptions([
                  'margin-top' => 10,
                  'margin-bottom' => 10,
                  'margin-left' => 10,
                  'margin-right' => 10,
              ]);

    return $pdf->download('jadwal_baju_' . Carbon::now()->format('Y-m-d') . '.pdf');
}


    public function destroy($id)
    {
        $baju = Baju::findOrFail($id);

        if ($baju->gambar && file_exists(public_path('assets/img/' . $baju->gambar))) {
            unlink(public_path('assets/img/' . $baju->gambar));
        }

        $baju->delete();
        return redirect()->route('admin.baju')->with('success', 'Data berhasil dihapus!');
    }
}