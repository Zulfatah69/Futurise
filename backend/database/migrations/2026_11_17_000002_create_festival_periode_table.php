<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festival_periode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->enum('tahap', ['pendaftaran', 'pengumpulan_karya', 'seleksi', 'pengumuman_finalis', 'final', 'pengumuman_pemenang', 'pameran']);
            $table->dateTime('mulai');
            $table->dateTime('selesai');
            $table->string('keterangan', 255)->nullable();
            $table->unique(['festival_id', 'tahap'], 'uq_festival_tahap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festival_periode');
    }
};