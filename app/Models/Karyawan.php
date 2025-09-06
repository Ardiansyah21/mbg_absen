<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tugas',
    ];

   public function absensis()
{
    return $this->hasMany(AbsensiKaryawan::class, 'karyawan_id');
}


}