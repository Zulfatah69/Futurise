<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booth_zona', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->string('lantai', 20)->nullable();
            $table->string('denah_file', 255)->nullable();
            $table->integer('urutan')->default(0);
            $table->unique(['festival_id', 'nama'], 'uq_festival_zona');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booth_zona');
    }
};