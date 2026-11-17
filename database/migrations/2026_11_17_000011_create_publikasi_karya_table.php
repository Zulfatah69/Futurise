<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasi_karya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->enum('kanal', ['website', 'galeri_online', 'media_sosial', 'katalog_cetak', 'layar_venue'])->default('website');
            $table->string('slug', 200)->nullable()->unique('uq_slug');
            $table->string('judul_publik', 200)->nullable();
            $table->string('ringkasan_publik', 500)->nullable();
            $table->foreignId('thumbnail_file_id')->nullable()->constrained('karya_file')->nullOnDelete();
            $table->enum('status', ['draft', 'menunggu_persetujuan', 'terjadwal', 'terbit', 'ditarik'])->default('draft');
            $table->dateTime('tanggal_terbit')->nullable();
            $table->dateTime('tanggal_berakhir')->nullable();
            $table->boolean('is_unggulan')->default(false);
            $table->integer('urutan_tampil')->default(0);
            $table->boolean('izin_peserta')->default(false);
            $table->dateTime('izin_tanggal')->nullable();
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->string('alasan_penarikan', 255)->nullable();
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->unsignedInteger('jumlah_dilihat')->default(0);
            $table->unsignedInteger('jumlah_suka')->default(0);
            $table->unsignedInteger('jumlah_unduhan')->default(0);
            $table->timestamps();
            $table->unique(['karya_id', 'kanal'], 'uq_karya_kanal');
            $table->index(['status', 'tanggal_terbit'], 'idx_pubkarya_status_terbit');
            $table->index(['is_unggulan', 'urutan_tampil'], 'idx_pubkarya_unggulan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_karya');
    }
};