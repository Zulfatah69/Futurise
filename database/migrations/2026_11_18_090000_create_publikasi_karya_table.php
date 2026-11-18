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
        Schema::create('publikasi_karya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karya')->cascadeOnDelete();
            $table->enum('kanal', [
                'website',
                'galeri_online',
                'media_sosial',
                'katalog_cetak',
                'layar_venue',
            ])->default('website')->index();
            $table->string('slug', 200)->unique();
            $table->string('judul_publik', 200);
            $table->string('ringkasan_publik', 500)->nullable();
            $table->foreignId('thumbnail_file_id')->nullable()->constrained('karya_file')->nullOnDelete();
            $table->enum('status', [
                'draft',
                'menunggu_persetujuan',
                'terjadwal',
                'terbit',
                'ditarik',
            ])->default('draft')->index();
            $table->dateTime('tanggal_terbit')->nullable();
            $table->dateTime('tanggal_berakhir')->nullable();
            $table->boolean('is_unggulan')->default(false)->index();
            $table->integer('urutan_tampil')->default(0)->index();
            $table->boolean('izin_peserta')->default(false);
            $table->dateTime('izin_tanggal')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('alasan_penarikan', 255)->nullable();
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->unsignedInteger('jumlah_dilihat')->default(0);
            $table->unsignedInteger('jumlah_suka')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publikasi_karya');
    }
};
