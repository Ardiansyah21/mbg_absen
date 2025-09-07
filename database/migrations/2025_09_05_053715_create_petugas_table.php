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
        Schema::create('jadwal_petugas', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel karyawans
            $table->foreignId('karyawan_id')
                  ->constrained('karyawans')
                  ->cascadeOnDelete();

            // Enum tugas (harus sama persis dengan di karyawans)
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
            ]);

            // Jam masuk dan pulang
            $table->time('jam_masuk');
            $table->time('jam_pulang')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_petugas');
    }
};