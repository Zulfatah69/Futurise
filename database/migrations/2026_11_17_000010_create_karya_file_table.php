<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->enum('jenis', ['utama', 'thumbnail', 'galeri', 'dokumen', 'video', 'lainnya'])->default('lainnya');
            $table->string('nama_asli', 255)->nullable();
            $table->string('path', 255)->nullable();
            $table->string('url_eksternal', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('ukuran_byte')->nullable();
            $table->integer('durasi_detik')->nullable();
            $table->integer('lebar')->nullable();
            $table->integer('tinggi')->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_publik')->default(true);
            $table->char('checksum', 64)->nullable();
            $table->foreignId('diunggah_oleh')->nullable()->constrained('peserta')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['karya_id', 'jenis'], 'idx_karya_file_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_file');
    }
};