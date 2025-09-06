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
        Schema::create('bajus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_baju'); // nama baju
            $table->enum('hari', [
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
            ]); // pilihan hari
            $table->text('deskripsi')->nullable(); // keterangan tambahan
            $table->string('gambar')->nullable(); // path gambar baju
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bajus');
    }
};