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
        Schema::create('absensi_karyawans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id'); // relasi ke tabel karyawan
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']); // contoh status
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();
            $table->string('tanda_tangan')->nullable(); // path atau base64 tanda tangan
            $table->string('hari'); // Senin, Selasa, dll
            $table->string('metode')->nullable(); // fingerprint / izin
            $table->decimal('latitude', 10, 7)->nullable(); // koordinat
            $table->decimal('longitude', 10, 7)->nullable(); // koordinat
            $table->timestamps();

            // foreign key
            $table->foreign('karyawan_id')
                  ->references('id')
                  ->on('karyawans')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_karyawans');
    }
};