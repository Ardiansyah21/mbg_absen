<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id(); 
            $table->string('nama');
         $table->enum('tugas', [
                // Tugas utama
                'Persiapan',
                'Memasak',
                'Packing',
                'Distribusi',
                'Kebersihan',
                'Pencucian',
                'Asisten Lapangan',

                // Koordinator
                'Koordinator Persiapan',
                'Koordinator Memasak',
                'Koordinator Packing',
                'Koordinator Distribusi',
                'Koordinator Kebersihan',
                'Koordinator Pencucian',
                'Koordinator Asisten Lapangan',
         ]);      $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};