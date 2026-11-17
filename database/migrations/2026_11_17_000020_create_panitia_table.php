<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panitia', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('no_telepon', 20)->nullable();
            $table->string('institusi', 150)->nullable();
            $table->enum('jenis', ['internal', 'relawan', 'profesional', 'mahasiswa_magang'])->default('relawan');
            $table->enum('ukuran_kaos', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->nullable();
            $table->string('kontak_darurat_nama', 100)->nullable();
            $table->string('kontak_darurat_telepon', 20)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('password_hash', 255)->nullable();
            $table->dateTime('terakhir_login')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panitia');
    }
};