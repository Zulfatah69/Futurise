<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('festival_sponsor', function (Blueprint $table) {
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('sponsor_id')->constrained('sponsors')->cascadeOnDelete();
            $table->enum('tingkat', ['platinum', 'gold', 'silver', 'bronze', 'mitra_media', 'pendukung']);
            $table->integer('urutan')->default(0);
            $table->primary(['festival_id', 'sponsor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('festival_sponsor');
    }
};