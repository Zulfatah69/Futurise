<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_mitra_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_mitra_id')->constrained('karya_mitra')->cascadeOnDelete();
            $table->enum('jenis', ['prototipe', 'uji_coba_pilot', 'implementasi', 'lisensi', 'investasi', 'publikasi_ilmiah', 'paten_hki', 'penghargaan', 'lainnya']);
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal')->nullable();
            $table->decimal('nilai', 14, 2)->nullable();
            $table->string('bukti_file', 255)->nullable();
            $table->string('url_bukti', 255)->nullable();
            $table->boolean('tampil_publik')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['karya_mitra_id', 'jenis'], 'idx_km_hasil_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_mitra_hasil');
    }
};