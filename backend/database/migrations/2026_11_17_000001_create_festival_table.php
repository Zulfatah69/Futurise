<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festival', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 150)->unique();
            $table->string('edisi', 30)->nullable();
            $table->year('tahun')->index('festival_idx_tahun');
            $table->string('tema', 200)->nullable();
            $table->string('tagline', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('penyelenggara', 150)->nullable();
            $table->enum('format', ['offline', 'online', 'hybrid'])->default('hybrid');
            $table->string('lokasi_utama', 200)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('zona_waktu', 40)->default('Asia/Jakarta');
            $table->string('logo', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('email_kontak', 100)->nullable();
            $table->string('whatsapp_kontak', 20)->nullable();
            $table->unsignedTinyInteger('maks_anggota_tim')->default(5);
            $table->unsignedTinyInteger('maks_karya_per_peserta')->default(1);
            $table->decimal('biaya_pendaftaran', 12, 2)->default(0);
            $table->enum('status', ['draft', 'pendaftaran', 'seleksi', 'berlangsung', 'selesai', 'arsip'])->default('draft')->index('festival_idx_status');
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festival');
    }
};