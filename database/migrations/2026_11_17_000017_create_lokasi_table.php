<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->enum('tipe', ['panggung', 'booth', 'ruang', 'virtual'])->default('booth');
            $table->string('gedung_area', 100)->nullable();
            $table->integer('kapasitas')->nullable();
            $table->string('url_virtual', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->boolean('aktif')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};