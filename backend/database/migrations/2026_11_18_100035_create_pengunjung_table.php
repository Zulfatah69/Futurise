<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengunjung', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('no_telepon', 20)->nullable();
            $table->enum('jenis_kelamin', ['laki_laki', 'perempuan', 'tidak_menyebutkan'])->default('tidak_menyebutkan');
            $table->year('tahun_lahir')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->enum('jenis', ['umum', 'pelajar', 'mahasiswa', 'profesional', 'investor', 'media', 'pemerintah', 'lainnya'])->default('umum')->index('idx_pengunjung_jenis');
            $table->string('institusi', 150)->nullable();
            $table->string('minat', 255)->nullable();
            $table->boolean('setuju_syarat')->default(false);
            $table->boolean('setuju_promosi')->default(false);
            $table->dateTime('email_terverifikasi_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengunjung');
    }
};