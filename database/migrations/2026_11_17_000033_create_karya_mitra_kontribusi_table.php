<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_mitra_kontribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_mitra_id')->constrained('karya_mitra')->cascadeOnDelete()->index('idx_km_kontribusi_kolab');
            $table->enum('jenis', ['dana', 'perangkat', 'akses_data', 'tenaga_ahli', 'tempat', 'publikasi', 'lisensi_software', 'lainnya']);
            $table->string('deskripsi', 255);
            $table->decimal('nilai', 14, 2)->nullable();
            $table->date('tanggal')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_mitra_kontribusi');
    }
};