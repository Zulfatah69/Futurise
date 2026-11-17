<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panitia_festival', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('panitia_id')->constrained('panitia')->cascadeOnDelete();
            $table->foreignId('divisi_id')->nullable()->constrained('divisi_panitia')->nullOnDelete();
            $table->enum('jabatan', ['ketua', 'wakil_ketua', 'sekretaris', 'bendahara', 'koordinator', 'anggota', 'relawan'])->default('anggota');
            $table->foreignId('atasan_id')->nullable()->constrained('panitia_festival')->nullOnDelete();
            $table->enum('status', ['mendaftar', 'diterima', 'aktif', 'nonaktif', 'ditolak', 'mundur'])->default('mendaftar');
            $table->date('tanggal_bergabung')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('sertifikat_file', 255)->nullable();
            $table->string('catatan', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['festival_id', 'panitia_id'], 'uq_festival_panitia');
            $table->index(['divisi_id', 'status'], 'idx_panitia_divisi_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panitia_festival');
    }
};