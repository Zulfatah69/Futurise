<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('no_telepon', 20)->nullable();
            $table->string('institusi', 150)->nullable();
            $table->string('jenis_peserta', 30)->nullable();
            $table->string('nomor_identitas', 30)->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};