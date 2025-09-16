<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_karyawans', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_karyawans', 'nama_pengganti')) {
                $table->string('nama_pengganti')->nullable()->after('hari');
            }
            if (!Schema::hasColumn('absensi_karyawans', 'keterangan_pengganti')) {
                $table->text('keterangan_pengganti')->nullable()->after('nama_pengganti');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_karyawans', function (Blueprint $table) {
            $table->dropColumn(['nama_pengganti', 'keterangan_pengganti']);
        });
    }
};