<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('karya_id')->nullable()->constrained('karya')->nullOnDelete()->index('idx_jadwal_karya');
            $table->foreignId('lokasi_id')->constrained('lokasi');
            $table->enum('jenis', ['presentasi', 'pameran', 'demo', 'penjurian', 'workshop', 'acara_umum'])->default('presentasi');
            $table->string('judul_sesi', 200)->nullable();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('urutan_tampil')->default(0);
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'ditunda', 'dibatalkan'])->default('terjadwal');
            $table->boolean('is_tampil_publik')->default(true);
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
            $table->index(['tanggal', 'lokasi_id', 'waktu_mulai'], 'idx_jadwal_tanggal_lokasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};