<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'jadwal_petugas';

    // Field yang bisa diisi massal
    protected $fillable = [
        'karyawan_id',
        'tugas',
        'jam_masuk',
        'jam_pulang',
    ];

    // Jika mau, bisa tambahkan accessor untuk format jam
    public function getJamMasukFormattedAttribute()
    {
        return date('H:i', strtotime($this->jam_masuk));
    }

    public function getJamPulangFormattedAttribute()
    {
        return $this->jam_pulang ? date('H:i', strtotime($this->jam_pulang)) : 'Selesai';
    }
   // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

}