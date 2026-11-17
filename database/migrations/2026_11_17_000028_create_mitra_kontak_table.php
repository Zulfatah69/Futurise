<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_kontak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('mitra')->cascadeOnDelete()->index('idx_mitrakontak_mitra');
            $table->string('nama', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->boolean('is_utama')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_kontak');
    }
};