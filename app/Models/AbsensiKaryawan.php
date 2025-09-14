<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKaryawan extends Model
{
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'status',
        'waktu_masuk',
        'waktu_keluar',
        'tanda_tangan',
        'hari',
        'nama_pengganti',        
        'keterangan_pengganti', 
    ];

    
 public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }



}