<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisi_panitia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->integer('kuota_anggota')->nullable();
            $table->integer('urutan')->default(0);
            $table->unique(['festival_id', 'nama'], 'uq_festival_divisi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisi_panitia');
    }
};