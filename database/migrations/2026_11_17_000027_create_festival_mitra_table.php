<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festival_mitra', function (Blueprint $table) {
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained('mitra')->cascadeOnDelete()->index('idx_festmitra_mitra');
            $table->enum('peran', ['mitra_media', 'mitra_komunitas', 'mitra_akademik', 'mitra_teknologi', 'mitra_pemerintah', 'mitra_lokasi', 'mitra_lainnya']);
            $table->string('bentuk_kontribusi', 255)->nullable();
            $table->enum('status', ['diundang', 'dikonfirmasi', 'aktif', 'selesai', 'mundur'])->default('diundang');
            $table->boolean('tampil_publik')->default(true);
            $table->integer('urutan')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('nomor_perjanjian', 100)->nullable();
            $table->string('dokumen_perjanjian', 255)->nullable();
            $table->string('catatan', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['festival_id', 'mitra_id', 'peran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festival_mitra');
    }
};