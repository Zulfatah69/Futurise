<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panitia_peran', function (Blueprint $table) {
            $table->foreignId('panitia_id')->constrained('panitia')->cascadeOnDelete();
            $table->foreignId('peran_id')->constrained('peran_akses')->cascadeOnDelete();
            $table->foreignId('festival_id')->nullable()->constrained('festival')->cascadeOnDelete();
            $table->foreignId('diberikan_oleh')->nullable()->constrained('panitia')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['panitia_id', 'peran_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panitia_peran');
    }
};