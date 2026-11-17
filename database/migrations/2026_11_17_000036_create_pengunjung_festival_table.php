<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengunjung_festival', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('pengunjung_id')->constrained('pengunjung')->cascadeOnDelete();
            $table->string('kode_tiket', 30)->unique();
            $table->enum('jenis_tiket', ['gratis', 'reguler', 'vip', 'undangan', 'media'])->default('gratis');
            $table->enum('mode_hadir', ['offline', 'online'])->default('offline');
            $table->decimal('harga', 12, 2)->default(0);
            $table->enum('status_bayar', ['gratis', 'menunggu', 'lunas', 'dibatalkan', 'refund'])->default('gratis');
            $table->enum('status', ['terdaftar', 'hadir', 'tidak_hadir', 'dibatalkan'])->default('terdaftar');
            $table->string('sumber_info', 100)->nullable();
            $table->timestamp('terdaftar_pada')->useCurrent();
            $table->unique(['festival_id', 'pengunjung_id'], 'uq_festival_pengunjung');
            $table->index(['festival_id', 'status'], 'idx_pf_fest_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengunjung_festival');
    }
};