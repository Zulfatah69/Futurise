<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referensi_institusi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('kategori', 30)->index(); // 'perguruan_tinggi' | 'smk'
            $table->string('npsn', 30)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kab_kota', 100)->nullable();
            $table->timestamps();

            $table->index('nama');
            $table->index(['kategori', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referensi_institusi');
    }
};
