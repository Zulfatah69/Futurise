<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panitia_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panitia_festival_id')->constrained('panitia_festival')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->nullOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal')->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['terjadwal', 'bertugas', 'selesai', 'absen', 'diganti'])->default('terjadwal');
            $table->dateTime('check_in_pada')->nullable();
            $table->string('catatan', 255)->nullable();
            $table->index(['tanggal', 'jam_mulai'], 'idx_tugas_tanggal_jam');
            $table->index('panitia_festival_id', 'idx_tugas_pf');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panitia_tugas');
    }
};