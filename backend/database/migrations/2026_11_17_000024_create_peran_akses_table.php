<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peran_akses', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->json('izin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peran_akses');
    }
};