<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('peserta')->restrictOnDelete();
            $table->string('judul', 200);
            $table->text('deskripsi');
            $table->string('kategori', 50);
            $table->string('teknologi', 255);
            $table->string('file_utama', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('url_demo', 255)->nullable();
            $table->string('lisensi', 50)->nullable();
            $table->boolean('persetujuan_publikasi')->default(false);
            $table->enum('status', [
                'draft',
                'diajukan',
                'dalam_pemeriksaan',
                'perlu_revisi',
                'lolos',
                'tidak_lolos',
            ])->default('draft')->index();
            $table->decimal('skor_akhir', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya');
    }
};
