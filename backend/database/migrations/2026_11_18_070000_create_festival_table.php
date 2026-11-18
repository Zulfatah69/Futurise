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
        Schema::create('festival', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 150)->unique();
            $table->string('edisi', 50);
            $table->year('tahun');
            $table->string('tema', 255)->nullable();
            $table->string('tagline', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('penyelenggara', 150)->default('APTIKOM Jawa Barat');
            $table->enum('format', ['luring', 'daring', 'hibrida'])->default('luring');
            $table->string('lokasi_utama', 255)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('zona_waktu', 50)->default('Asia/Jakarta');
            $table->string('logo', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('email_kontak', 100)->nullable();
            $table->boolean('aktif')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('festival');
    }
};
