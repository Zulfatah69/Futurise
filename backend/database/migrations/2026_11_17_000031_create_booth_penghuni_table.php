<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booth_penghuni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booth_id')->constrained('booth')->cascadeOnDelete();
            $table->foreignId('karya_id')->nullable()->constrained('karya')->nullOnDelete()->index('idx_bp_karya');
            $table->foreignId('sponsor_id')->nullable()->constrained('sponsors')->nullOnDelete();
            $table->foreignId('mitra_id')->nullable()->constrained('mitra')->nullOnDelete();
            $table->string('nama_tampil', 150)->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['dipesan', 'dikonfirmasi', 'check_in', 'selesai', 'batal'])->default('dipesan');
            $table->dateTime('check_in_pada')->nullable();
            $table->string('catatan', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['booth_id', 'tanggal', 'jam_mulai'], 'idx_bp_booth_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booth_penghuni');
    }
};