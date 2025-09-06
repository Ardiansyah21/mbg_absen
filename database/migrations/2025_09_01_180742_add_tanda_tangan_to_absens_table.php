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
    Schema::table('absensi_karyawans', function (Blueprint $table) {
        $table->longText('tanda_tangan')->change();
    });
}

public function down(): void
{
    Schema::table('absensi_karyawans', function (Blueprint $table) {
        $table->string('tanda_tangan')->change();
    });
}


};