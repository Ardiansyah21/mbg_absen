<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Baju;
use App\Models\Peraturan;
use App\Models\Petugas;
use App\Models\Karyawan;



use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
public function index()
{
    // Ambil semua jadwal petugas beserta data karyawan
    $petugas = Petugas::with('karyawan')
->orderByRaw("FIELD(tugas, 
        'Persiapan','Pengolahan','Pemorsian','Distribusi','Kebersihan','Pencucian','Asisten Lapangan',
        'Koordinator Persiapan','Koordinator Pengolahan','Koordinator Pemorsian','Koordinator Distribusi','Koordinator Kebersihan','Koordinator Pencucian',
        'PJ Persiapan','PJ Pengolahan','PJ Pemorsian','PJ Distribusi','PJ Kebersihan','PJ Pencucian'
    )")        ->get();

    // Kelompokkan petugas berdasarkan tugas agar mudah ditampilkan per tabel
    $petugasByTugas = $petugas->groupBy('tugas');

    // Ambil semua karyawan (misal untuk dropdown atau referensi)
    $karyawans = Karyawan::all();

    // Ambil data lain kalau perlu, contohnya:
    $jadwals = Baju::orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat')")->get();
    $peraturans = Peraturan::orderBy('tugas')->get();

    // Compact semua data sekaligus
    return view('page', compact('petugasByTugas', 'karyawans', 'jadwals', 'peraturans'));
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
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        //
    }
}