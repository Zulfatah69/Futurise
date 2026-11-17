<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->nullable()->constrained('festival')->nullOnDelete();
            $table->foreignId('peserta_id')->nullable()->constrained('peserta')->nullOnDelete();
            $table->string('judul', 200)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 50)->nullable();
            $table->string('teknologi', 255)->nullable();
            $table->string('file_utama', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('url_demo', 255)->nullable();
            $table->string('lisensi', 50)->nullable();
            $table->boolean('persetujuan_publikasi')->nullable();
            $table->string('status', 20)->nullable();
            $table->decimal('skor_akhir', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya');
    }
};