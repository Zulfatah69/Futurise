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
        Schema::create('festival_periode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->string('tahap', 100);
            $table->dateTime('mulai');
            $table->dateTime('selesai');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('festival_periode');
    }
};
