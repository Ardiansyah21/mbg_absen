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

            // Relasi ke karyawan
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')
                  ->references('id')
                  ->on('karyawans')
                  ->onDelete('cascade');

            // Tugas
            $table->enum('tugas', ['Persiapan','Memasak','Packing','Distribusi','Kebersihan','Pencucian']);

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
        Schema::dropIfExists('petugas');
    }
};