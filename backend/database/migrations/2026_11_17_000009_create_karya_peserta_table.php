<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_peserta', function (Blueprint $table) {
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete()->index('idx_karya_peserta_peserta');
            $table->string('peran', 50)->nullable();
            $table->boolean('is_ketua')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['karya_id', 'peserta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_peserta');
    }
};