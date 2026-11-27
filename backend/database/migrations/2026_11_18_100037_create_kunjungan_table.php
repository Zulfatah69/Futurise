<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengunjung_festival_id')->constrained('pengunjung_festival')->cascadeOnDelete();
            $table->foreignId('zona_id')->nullable()->constrained('booth_zona')->nullOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal')->nullOnDelete();
            $table->dateTime('waktu_masuk')->useCurrent();
            $table->dateTime('waktu_keluar')->nullable();
            $table->enum('metode', ['qr', 'manual', 'online'])->default('qr');
            $table->index('waktu_masuk', 'idx_kunjungan_waktu');
            $table->index('pengunjung_festival_id', 'idx_kunjungan_pf');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};