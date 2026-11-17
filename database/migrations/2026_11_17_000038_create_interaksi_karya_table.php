<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interaksi_karya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengunjung_festival_id')->constrained('pengunjung_festival')->cascadeOnDelete();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->enum('jenis', ['suka', 'vote', 'simpan', 'ulasan']);
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('komentar', 500)->nullable();
            $table->enum('status_moderasi', ['menunggu', 'tampil', 'disembunyikan'])->default('menunggu');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['pengunjung_festival_id', 'karya_id', 'jenis'], 'uq_interaksi');
            $table->index(['karya_id', 'jenis'], 'idx_interaksi_karya_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interaksi_karya');
    }
};