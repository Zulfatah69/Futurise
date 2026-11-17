<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booth', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festival_id')->constrained('festival')->cascadeOnDelete();
            $table->foreignId('zona_id')->nullable()->constrained('booth_zona')->nullOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->nullOnDelete();
            $table->string('kode', 20);
            $table->enum('tipe', ['standar', 'premium', 'meja', 'virtual', 'outdoor'])->default('standar');
            $table->decimal('panjang_m', 5, 2)->nullable();
            $table->decimal('lebar_m', 5, 2)->nullable();
            $table->decimal('pos_x', 7, 2)->nullable();
            $table->decimal('pos_y', 7, 2)->nullable();
            $table->integer('listrik_watt')->nullable();
            $table->boolean('ada_internet')->default(false);
            $table->string('fasilitas', 255)->nullable();
            $table->string('url_virtual', 255)->nullable();
            $table->enum('status', ['tersedia', 'dipesan', 'terisi', 'tidak_tersedia'])->default('tersedia');
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
            $table->unique(['festival_id', 'kode'], 'uq_festival_kode');
            $table->index(['festival_id', 'status'], 'idx_booth_fest_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booth');
    }
};