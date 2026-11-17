<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publikasi_id')->constrained('publikasi_karya')->cascadeOnDelete();
            $table->string('status_lama', 30)->nullable();
            $table->string('status_baru', 30);
            $table->unsignedBigInteger('diubah_oleh')->nullable();
            $table->string('catatan', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['publikasi_id', 'created_at'], 'idx_publog_publikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_log');
    }
};