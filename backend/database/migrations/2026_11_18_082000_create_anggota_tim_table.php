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
        Schema::create('anggota_tim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->string('nama_lengkap', 100);
            $table->string('peran', 50)->nullable();
            $table->boolean('is_ketua')->default(false);
            $table->string('email', 100)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('institusi', 150)->nullable();
            $table->string('jenis_peserta', 30)->nullable();
            $table->string('nomor_identitas', 30)->nullable();
            $table->string('foto', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_tim');
    }
};
