<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->foreignId('juri_id')->constrained('juri')->cascadeOnDelete();
            $table->enum('tahap', ['seleksi', 'final'])->default('seleksi');
            $table->decimal('total_skor', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->dateTime('dinilai_pada')->nullable();
            $table->timestamps();
            $table->unique(['karya_id', 'juri_id', 'tahap'], 'uq_juri_karya_tahap');
            $table->index(['karya_id', 'tahap'], 'idx_penilaian_karya_tahap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};