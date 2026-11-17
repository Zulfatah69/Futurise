<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festival_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->integer('kuota')->nullable();
            $table->integer('urutan')->default(0);
            $table->unique(['festival_id', 'nama'], 'uq_festival_kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festival_kategori');
    }
};