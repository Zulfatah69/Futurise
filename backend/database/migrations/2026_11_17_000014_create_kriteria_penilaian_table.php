<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->decimal('bobot', 5, 2);
            $table->decimal('skor_min', 5, 2)->default(0);
            $table->decimal('skor_maks', 5, 2)->default(100);
            $table->integer('urutan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria_penilaian');
    }
};