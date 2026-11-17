<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 150)->unique();
            $table->enum('jenis', ['media', 'komunitas', 'kampus', 'pemerintah', 'teknologi', 'industri', 'lembaga_nirlaba', 'lainnya'])->default('lainnya')->index('idx_mitra_jenis');
            $table->string('deskripsi', 500)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->json('media_sosial')->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra');
    }
};