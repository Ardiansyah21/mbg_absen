<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiKaryawanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\BajuController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\PetugasController;











/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'index'])->name('page');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/absensi', [AbsensiKaryawanController::class, 'index'])->name('absensi.index');
Route::post('/absensi/store', [AbsensiKaryawanController::class, 'store'])->name('store.absensi');
Route::post('/absensi/fingerprint', [AbsensiKaryawanController::class, 'fingerprint'])->name('absensi.fingerprint');
Route::post('/absensi/reset', [AbsensiKaryawanController::class, 'reset'])->name('absensi.reset');
Route::post('/absensi/izin', [AbsensiKaryawanController::class, 'izin'])->name('absensi.izin');

Route::get('/absensi', [AbsensiKaryawanController::class, 'index'])->name('absensi.index');
Route::post('/absensi/store', [AbsensiKaryawanController::class, 'store'])->name('store.absensi');
Route::get('/absensi/{id}/edit', [AbsensiKaryawanController::class, 'edit'])->name('absensi.edit');
Route::put('/absensi/{id}', [AbsensiKaryawanController::class, 'update'])->name('absensi.update');
Route::delete('/absensi/{id}', [AbsensiKaryawanController::class, 'destroy'])->name('absensi.destroy');



Route::middleware(['auth', 'admin'])->group(function () {
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin', [AdminController::class, 'index'])->name('admin');

Route::get('/admin/absensi', [AdminController::class, 'absen'])->name('admin.absensi');
Route::get('/admin/absensi/all', [AdminController::class, 'allAbsensi'])->name('absensi.all');
Route::get('/admin/rekap/pdf', [AdminController::class, 'exportPdf'])->name('admin.rekap.pdf');
Route::get('admin/absen/export-pdf-harian', [AdminController::class, 'exportPDFPerHari'])->name('admin.export-pdf-harian');

Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
Route::get('/admin/karyawan/export-pdf', [KaryawanController::class, 'exportPDF'])->name('karyawan.pdf');



Route::get('/bajus', [BajuController::class, 'index'])->name('admin.baju');
Route::post('/bajus/store', [BajuController::class, 'store'])->name('bajus.store');
Route::post('/bajus/{id}/update', [BajuController::class, 'update'])->name('bajus.update');
Route::delete('/bajus/delete/{id}', [BajuController::class, 'destroy'])->name('bajus.destroy');
Route::get('/admin/baju/export', [BajuController::class, 'exportPDF'])->name('admin.baju.export');


Route::get('peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::post('peraturan/store', [PeraturanController::class, 'store'])->name('peraturan.store');
Route::post('peraturan/update/{id}', [PeraturanController::class, 'update'])->name('peraturan.update');
Route::delete('peraturan/destroy/{id}', [PeraturanController::class, 'destroy'])->name('peraturan.destroy');
Route::get('/admin/peraturan/export', [PeraturanController::class, 'exportPDF'])->name('admin.peraturan.export');


Route::get('/petugas', [PetugasController::class, 'index'])->name('admin.petugas');
Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
Route::post('/petugas/update/{id}', [PetugasController::class, 'update'])->name('petugas.update');
Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
Route::get('/admin/petugas/export', [PetugasController::class, 'exportPDF'])->name('admin.petugas.export');

   
});