<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_mitra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained('mitra')->restrictOnDelete();
            $table->enum('peran', ['pembimbing', 'penyedia_teknologi', 'penyedia_data', 'pendanaan', 'produksi', 'uji_coba', 'distribusi', 'mentor_bisnis', 'lainnya']);
            $table->string('ringkasan', 500)->nullable();
            $table->enum('status', ['diusulkan', 'disetujui', 'aktif', 'selesai', 'ditolak', 'dibatalkan'])->default('diusulkan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('persetujuan_mitra')->default(false);
            $table->boolean('persetujuan_peserta')->default(false);
            $table->boolean('tampil_publik')->default(true);
            $table->enum('kepemilikan_hki', ['peserta', 'mitra', 'bersama', 'belum_ditentukan'])->default('belum_ditentukan');
            $table->string('nomor_perjanjian', 100)->nullable();
            $table->string('dokumen_perjanjian', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->unique(['karya_id', 'mitra_id', 'peran'], 'uq_karya_mitra_peran');
            $table->index(['mitra_id', 'status'], 'idx_karyamitra_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_mitra');
    }
};