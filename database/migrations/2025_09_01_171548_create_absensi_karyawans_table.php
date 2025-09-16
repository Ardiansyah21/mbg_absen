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

            // Karyawan bisa null agar histori tidak hilang saat karyawan dihapus
          $table->unsignedBigInteger('karyawan_id')->nullable();

            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']); // contoh status
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();
            $table->string('tanda_tangan')->nullable(); // path atau base64 tanda tangan
            $table->string('hari');
            $table->string('nama_pengganti')->nullable();
            $table->text('keterangan_pengganti')->nullable();
            $table->string('metode')->nullable(); // fingerprint / izin
            $table->decimal('latitude', 10, 7)->nullable(); // koordinat
            $table->decimal('longitude', 10, 7)->nullable(); // koordinat
            $table->timestamps();

            // Foreign key dengan set null saat karyawan dihapus
$table->foreign('karyawan_id')
      ->references('id')
      ->on('karyawans')
      ->onDelete('set null'); // atau 'no action'


            // Index untuk pencarian cepat berdasarkan karyawan
            $table->index('karyawan_id');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_karyawans', function (Blueprint $table) {
            $table->dropForeign(['karyawan_id']);
            $table->dropIndex(['karyawan_id']);
            $table->dropIndex(['tanggal']);
        });

        Schema::dropIfExists('absensi_karyawans');
    }
};